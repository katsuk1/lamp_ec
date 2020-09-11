<?php
//設定ファイル読み込み
require_once '../../conf/const.php';
// userデータに関する関数ファイルの読み込み
require MODEL_PATH . 'user.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'common.php';
// DBに関する関数ファイルを読み込み
require_once MODEL_PATH . 'db.php';

// PDO取得
$db = get_db_connect();

// 指定したユーザーIDのユーザーデータを取得
$user = get_user($db, 1);
if ($user["name"] === "sampleuser" && $user['password'] === "password" && $user["type"] === 2){
  //var_dump($user);
  echo 'OK';
} else {
  echo 'NG';
  //var_dump($user);
}

echo "<br>";

// 指定したユーザー名のユーザーデータを取得
$user = get_user_by_name($db, 'sampleuser');
if ($user["user_id"] === 1 && $user["name"] === "sampleuser" && $user['password'] === "password" && $user["type"] === 2){
  //var_dump($user);
  echo 'OK';
} else {
  echo 'NG';
  //var_dump($user);
}

echo "<br>";

// ログインできた場合、セッション変数にユーザーIDをセット
login_as($db, 'sampleuser', 'password');
if(get_session('user_id') === 1){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// ログインユーザーのデータを取得
$user = get_login_user($db);
if ($user["user_id"] === 1 && $user["name"] === "sampleuser" && $user['password'] === "password" && $user["type"] === 2){
  //var_dump($user);
  echo 'OK';
  isset_delete_session('user_id');
} else {
  echo 'NG';
  //var_dump($user);
}

echo '<br>';

// ログインユーザーが管理ユーザーかチェック
$admin = array('type' => 1);
if (is_admin($admin) === true){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

/* 各種バリデーション
-------------------------------------*/

// ユーザー名とパスワード
if (is_valid_user($db, 'sampleuser9', 'password', 'password') === true){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// ユーザー名(文字数、同名チェック、半角英数字チェック)
if (is_valid_user_name($db, 'sampleuser9') === true){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 6文字以下
if (is_valid_user_name($db, 'hoge') === false){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 100文字ぴったり
if (is_valid_user_name($db, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa') === true){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 100文以上
if (is_valid_user_name($db, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa') === false){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// パスワード(文字数チェック、半角英数字チェック、確認用と一致するかチェック)
if (is_valid_password('password', 'password') === true) {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 同名ユーザーチェック
if (is_valid_same_user($db, 'sampleuser') === false){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 同名ユーザーチェック(同名ユーザーがいない場合)
if (is_valid_same_user($db, 'sampleuser10') === true){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 指定されたユーザー名と同じ名前のユーザーが登録されていた場合取得
$user = select_same_user($db, 'sampleuser');
if ($user['name'] === 'sampleuser') {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";