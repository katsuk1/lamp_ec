<?php
//設定ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'common.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// dbデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';
// itemデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックのため、セッション開始
session_start();

// ログインチェック
if(is_logined() === false){
  // ログインしていなければログインページへリダイレクト
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

// 管理ユーザーかチェック
if(is_admin($user) === false){
  // 管理ユーザーでなければログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// postで送信された商品IDを取得
$item_id = get_post('item_id');
// fileタイプで送信された商品画像のファイル名を取得
$image = get_file('new_img');

// 商品画像の更新
if(regist_item_image($db, $image, $item_id) === true){
    set_message('画像を更新しました。');
} else {
    set_message('画像の更新に失敗しました。');
}

// 管理ページへリダイレクト
redirect_to(ADMIN_URL);