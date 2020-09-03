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

// postで送信されたユーザー名を取得
$name     = get_post('name');
// postで送信されたパスワードを取得
$password = get_post('password');

// PDOを取得
$db       = get_db_connect();

// ログインし、ユーザーデータを取得後セッション変数にユーザーIDをセット
$user = login_as($db, $name, $password);
if($user === false){
    set_error('ログインに失敗しました。');
    // ログインに失敗した場合、ログインページへリダイレクト
    redirect_to(LOGIN_URL);
}

set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
    // ログインしたユーザーが管理ユーザーであれば商品管理ページへリダイレクト
    redirect_to(ADMIN_URL);
}
// ログインに成功した場合、商品一覧ページへリダイレクト
redirect_to(ITEMLIST_URL);


/*
//例外処理
try {
    
    //DBに接続
    $dbh = get_db_connect();
    
    //値がpostされた場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //ユーザーネームとパスワードのpost値を取得
        $username  = get_post_element('username');
        $password  = get_post_element('password');
        //var_dump($password);
        
        //DBに同名のユーザー名があれば取得
        $row = select_users($dbh, $username);
        
        //ユーザー名とパスワードが管理者かどうか
        if ($username === 'admin' && $password === 'admin') {
            $admin = true;
        }
            
        //管理者であれば管理画面に
        if ($admin === true) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id']  = null;
            header ('Location: admin.php');
            exit;
        }
        
        //var_dump($row);
        //user_nameのエラーチェック
        if (empty($username)) {
            $err_msg[] = 'ユーザー名が入力されていません';
        }
        
        //パスワードの正規表現と空チェック
        if (empty($password)) {
            $err_msg[] = 'パスワードが入力されていません';
        }
        
        //ユーザー名をCookieへ保存
        //setcookie('user_name', $username, time() + 60 * 60 * 24 * 365);
        
        //同名のユーザー名が存在しているか確認
        if (!isset($row['username'])) {
            $err_msg[] = 'ユーザー名又はパスワードが間違っています';
        }
        
        //エラーがなければ
        if (count($err_msg) === 0) {
            
            //ハッシュ化されたパスワードとの照合ができた場合
            if (password_verify($password, $row['password'])) {
                //セッションにユーザーネームとユーザーIDを保存
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id']  = $row['user_id'];
                //商品一覧へリダイレクト
                header ('Location: itemlist.php');
                exit;
            } else {
                $err_msg[] = 'ユーザー名又はパスワードが間違っています。';
            }
            
            
        }
        
        }
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:' . $e->getMessage();
}
*/