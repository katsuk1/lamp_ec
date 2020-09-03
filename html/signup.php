<?php
//設定ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'common.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';


//セッション開始
session_start();

//ログインチェック
if (is_logined() === true) {
    //ログインしていれば商品一覧へリダイレクト
    redirect_to(ITEMLIST_URL);
}

// トークンを生成し、セッション変数に設定
$token = get_csrf_token();

// ビューの読み込み
include_once VIEW_PATH . 'signup_view.php';