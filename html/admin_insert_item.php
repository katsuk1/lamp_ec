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

// 管理ユーザーかチェック
if(is_admin($user) === false){
  // 管理ユーザーでなければログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// postで送信された商品名を取得
$name = get_post('name');
// postで送信された価格を取得
$price = get_post('price');
// postで送信されたステータスを取得
$status = get_post('status');
// postで送信された在庫数を取得
$stock = get_post('stock');
// fileタイプで送信された商品画像のファイル名を取得
$image = get_file('new_img');
// postで送信された地域を取得
$area = get_post('area');
// postで送信された味を取得
$taste = get_post('taste');
// postで送信された濃さを取得
$intensity = get_post('taste_intensity');
// postで送信されたコメントを取得
$comment = get_post('comment');

//dd($status);

// 商品のバリデーション、itemsテーブルへの登録
if(regist_item($db, $name, $price, $stock, $status, $image, $area, $taste, $intensity, $comment)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

// 管理ページへリダイレクト
redirect_to(ADMIN_URL);