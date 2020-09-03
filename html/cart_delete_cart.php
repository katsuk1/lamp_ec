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
// ログインユーザーのユーザーデータを取得
$user = get_login_user($db);

// postで送信されたカートIDを取得
$cart_id = get_post('cart_id');

// カート内の商品を削除
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
} else {
  set_error('カートの削除に失敗しました。');
}

// カートページへリダイレクト
redirect_to(CART_URL);