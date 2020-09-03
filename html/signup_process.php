<?php
//定数ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'common.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';

//セッション開始
session_start();

//ログインチェック
if (is_logined() === true) {
    //ログインしていれば商品一覧へリダイレクト
    redirect_to(ITEMLIST_URL);
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

// ユーザー名を取得
$name                  = get_post('name');
// パスワードを取得
$password              = get_post('password');
// 確認用パスワードを取得
$password_confirmation = get_post('password_confirmation');

// PDOを取得
$db = get_db_connect();

// 例外処理
try{
    // ユーザー名、パスワードのバリデーション、usersテーブルへ書き込み
    $result = regist_user($db, $name, $password, $password_confirmation);
    if( $result=== false){
        set_error('ユーザー登録に失敗しました。');
        // 登録に失敗した場合、ユーザー登録ページにリダイレクト
        redirect_to(SIGNUP_URL);
    }
    
}catch(PDOException $e){
    set_error('ユーザー登録に失敗しました。');
    // 登録に失敗した場合、ユーザー登録ページにリダイレクト
    redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');

// ユーザー登録が完了した場合、ログインしてセッション変数にユーザーIDをセット
$user = login_as($db, $name, $password);
if( $user === false){
    set_error('ログインに失敗しました。');
    // ログインに失敗した場合、ログインページへリダイレクト
    redirect_to(LOGIN_URL);
}
// 商品一覧ページにリダイレクト
redirect_to(ITEMLIST_URL);



/*
//例外処理
try {
    //DBに接続
    $dbh = get_db_connect();
    
    //フォームからpostで送信された場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //ユーザー名とパスワードのpost値を取得
        $user_name = get_post_element('user_name');
        $password  = get_post_element('password');
        
        //user_nameが既に使用されていた場合行数を取得
        $row = select_users($dbh, $user_name);
        //var_dump($row);
        //user_nameのエラーチェック
        if (empty($user_name)) {
            $err_msg[] = 'ユーザー名が入力されていません';
        } else if($row['COUNT(username)'] > 0) {
            $err_msg[] = '既に登録されているユーザー名です。別のユーザー名でご登録ください';
        } else {
            if (length_check($user_name, 20) === false)
                $err_msg[] = 'ユーザー名は20文字以内で入力してください';
        }
        
        //パスワードの正規表現と空チェック
        if (empty($password)) {
            $err_msg[] = 'パスワードが入力されていません';
        } else if (preg_match($pass_regex, $password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $err_msg[] = 'パスワードは半角英数字6文字以上で入力してください';
        }
        
        //エラーチェック後エラーがなければ
        if (count($err_msg) === 0) {
            //ec_usersテーブルに書き込み
            insert_users($dbh, $user_name, $password, $date);
            $msg = '新規登録が完了しました';
            
        }
        //var_dump($err_msg);
    }
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:' . $e->getMessage();
}
*/