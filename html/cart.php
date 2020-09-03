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


// ログインチェックのため、セッション開始
session_start();

// ログインチェック
if(is_logined() === false){
  // ログインしていない場合、ログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
// ログインユーザーのユーザーデータを取得
$user = get_login_user($db);

// ログインユーザーのカートデータを取得
$carts = get_user_carts($db, $user['user_id']);

// カート内商品の合計価格を取得
$total_price = sum_carts($carts);

// トークンを生成し、セッション変数に設定
$token = get_csrf_token();

// ビューの読み込み
include_once VIEW_PATH . 'cart_view.php';


/*
//例外処理
try {
    //DB接続
    $dbh = get_db_connect();

    //フォームからpostで送信された場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //どのフォームから送信されたかを$sql_kindに入れる
        $sql_kind = get_post_element('sql_kind');
        
        //hiddenで渡されたitem_idを代入
        $item_id = get_post_element('item_id');
        
        //削除ボタンからの場合
        if ($sql_kind === 'delete') {
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                
                //ec_cartsテーブルのレコード削除
                delete_carts($dbh, $item_id, $user_id);
                $msg = '商品をカートから削除しました';
            }
        }
        
        //数量変更フォームからの場合
        if ($sql_kind === 'update_amount') {
            
            //hiddenで渡された購入予定数量と在庫を代入
            $amount = get_post_element('amount');
            $stock  = get_post_element('stock');
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //数量のエラーチェック
            if (empty($amount)) {
                $err_msg[] = '数量を入力してください';
            } else if (preg_match($int_regex, $amount) === 0) {
                $err_msg[] = '数量は半角数字で入力してください';
            } else if (length_check($amount, 10) === false) {
                $err_msg[] = '価格は10桁以内で入力してください';
            } else if ($amount > $stock) {
                $err_msg[] = '数量が在庫数を上回っています';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                
                //ec_cartsテーブルの数量を更新
                update_amount($dbh, $amount, $date, $item_id, $user_id);
                $msg = '数量を変更しました';
            }
        }
        
    }
    
    //カート内の商品情報を取得
    $data = get_data_carts($dbh, $data, $user_id);
    //var_dump($data);
    
    //価格、在庫、数量を配列で取得
    foreach($data as $value) {
        $price[]   = $value['price'];
        $quantity[]= $value['amount'];
    }
    
    //カートに商品が入っているかどうか
    if (empty($data)) {
        $err_msg[] = 'カートに商品が入っていません';
    } else {
        //カート内の商品価格の合計を算出
        $array_sum = sum_price_carts($price, $quantity);
        //var_dump($array_sum);
    }
    
    
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'. $e -> getMessage();
}
*/