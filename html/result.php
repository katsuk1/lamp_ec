<?php
//設定ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'common.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// dbデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';
// cartデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'cart.php';
// itemデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'item.php';
// historyデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'history.php';



// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// hiddenで送信されたトークンを取得
$token = get_post('csrf_token');
// トークンのチェック
if(is_valid_csrf_token($token) === false){
  // 正しくなければログインページへリダイレクト
  redirect_to(LOGIN_URL);
}
// セッション変数に設定したトークンを削除
unset($_SESSION['csrf_token']);

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得 
$user = get_login_user($db);

// PDOを利用してログインユーザーのカートデータを取得
$carts = get_user_carts($db, $user['user_id']);

// カート内の合計価格を取得
$total_price = sum_carts($carts);

// トランザクション開始
$db->beginTransaction();

// 購入履歴テーブルに登録
if(insert_purchase_history($db, $user['user_id'], $total_price) === false){
  set_error('商品の購入に失敗しました。');
  // 失敗した場合ロールバック処理
  $db->rollback();
  // 失敗した場合カートページにリダイレクト
  redirect_to(CART_URL);
}
// 最新の購入履歴IDを取得
$history_id = get_last_insert_id($db);

// 購入明細テーブルに登録
if(for_insert_purchase_detail($db, $history_id, $carts) === false){
  set_error('商品の購入に失敗しました。');
  // 失敗した場合ロールバック処理
  $db->rollback();
  // 失敗した場合カートページにリダイレクト
  redirect_to(CART_URL);
}

// 商品を購入し、在庫数の更新、カートから削除
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  // ロールバック処理
  $db->rollback();
  // 失敗した場合カートページにリダイレクト
  redirect_to(CART_URL);
} 
// コミット処理
$db->commit();

// ビューの読み込み
include_once VIEW_PATH . 'result_view.php';

/*
//例外処理
try {
    //DB接続
    $dbh = get_db_connect();
    
    //値がpostされた場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //hiddenで渡されたsql_kindを取得
        $sql_kind = get_post_element('sql_kind');
        //hiddenで渡されたカート内商品の合計価格を取得
        $sum = get_post_element('sum');
        
        //購入ボタンの場合
        if ($sql_kind === 'buy') {
            
            //ec_cartsからデータを取得
            $data = get_data_carts($dbh, $data, $user_id);
            //var_dump($data);
            
            //価格、在庫、数量を配列で取得
            foreach($data as $value) {
                $stock[]    = $value['stock'];
                $amount[]   = $value['amount'];
                $item_id[]  = $value['item_id'];
            }
            //var_dump($item_id);
            
            //配列のキー数を取得
            $total_index = max(count($stock), count($amount));
            //var_dump($total_index);
            //在庫から購入予定数を引いた差の配列を取得
            for ($i = 0; $i < $total_index; $i++) {
                $differ[] = ($stock[$i] - $amount[$i]);
            }
            //var_dump($differ);
            //トランザクション開始
                $dbh -> beginTransaction();
                
                try {
                    //ec_cartsテーブルのデータ削除
                    delete_carts_all($dbh, $user_id);
                    //ec_item_stockテーブルの在庫数を更新
                    update_stock_result($dbh, $differ, $date, $item_id, $total_index);
                    //ec_item_historyテーブルへの書き込み
                    insert_history($dbh, $user_id, $item_id, $amount, $date, $total_index);
                    
                    //コミット処理
                    $dbh -> commit();
                    $msg = 'ご購入ありがとうございました。またのご利用をお待ちしております。';
                
                } catch (PDOException $e) {
                    //ロールバック処理
                    $dbh -> rollback();
                    throw $e;
                }

        }
        
    }
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'. $e -> getMessage();
}
*/
