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

//phpinfo();

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// 管理ユーザーかチェック
if(is_admin($user) === false){
  // 管理ユーザーでなければログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// 全ユーザーデータを取得
$users = get_all_user($db);

// ビューの読み込み
include_once VIEW_PATH . 'user_admin_view.php';