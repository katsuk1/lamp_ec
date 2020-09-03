<?php
//設定ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'common.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// dbデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';
// historyデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'history.php';


// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// ログインユーザーの購入履歴データを取得
if(is_admin($user) === true){
  // 管理ユーザーであれば全ユーザーの購入履歴データを取得
  $histories = get_all_histories($db);
  //var_dump($histories);
} else {
  // 一般ユーザーであればログインユーザーの購入履歴データを取得
  $histories = get_user_histories($db, $user['user_id']);
}

// トークンを生成し、セッション変数に設定
$token = get_csrf_token();

// ビューの読み込み
include_once VIEW_PATH . 'history_view.php';

/*
//例外処理
try {
    //DB接続
    $dbh = get_db_connect();
    
    //値がpostされた場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //postされた商品IDとフォーム種別を取得
        $item_id  = get_post_element('item_id');
        $sql_kind = get_post_element('sql_kind');
        
        //カートの場合
        if ($sql_kind === 'cart') {
            
            //既にカートに追加しているかどうか
            $row = select_carts($dbh, $item_id, $user_id);
            //var_dump($row);
            
            //カート内商品のデータを取得
            $carts_data = get_data_carts($dbh, $user_id, $item_id);
            //var_dump($carts_data);
            
            //カート内商品の購入予定数と在庫を取得
            $quantity = $carts_data['amount'];
            $stock    = $carts_data['stock'];
            //var_dump($quantity);
            //var_dump($stock);
            
            //既にカートに追加されていなければ
            if ($row['COUNT(*)'] === 0) {
                //cartテーブルに書き込み
                insert_carts($dbh, $user_id, $item_id, $amount, $date);
                $msg = '商品をカートに追加しました';
            } else if ($quantity === $stock) {
                $err_msg[] = 'カート内の商品数が在庫を上回るので商品をカートに追加できません';
            } else if ($row['COUNT(*)'] > 0){
                //カートに追加されていたらamountに+1
                update_carts($dbh, $date, $item_id, $user_id);
                $msg = 'カートの商品数を更新しました';
            }
    
        }
        
    }
    
    //購入履歴データを取得
    $data = get_data_history($dbh, $data, $user_id);
    //var_dump($data);
    
    //購入履歴があるかどうか
    if (empty($data)) {
        $err_msg[] = 'まだ購入履歴はありません';
    }
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'. $e -> getMessage();
}
*/
