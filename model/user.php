<?php
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'common.php';
// DBに関する関数ファイルを読み込み
require_once MODEL_PATH . 'db.php';

/**
 * 指定したユーザーIDのユーザーデータを取得
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 * @return array 結果配列データ
 */
function get_user($db, $user_id){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      ec_users
    WHERE
      user_id = :user_id
    LIMIT 1
  ";
  $params = array(':user_id' => $user_id);
  return fetch_query($db, $sql, $params);
}

/**
 * 指定したユーザー名のユーザーデータを配列で取得
 * 
 * @param obj $db PDO
 * @param str $name ユーザー名
 * @return array ユーザー配列データ
 */
function get_user_by_name($db, $name){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      ec_users
    WHERE
      name = :name
    LIMIT 1
  ";
  $params = array(':name' => $name); 
  return fetch_query($db, $sql, $params);
}

/**
 * 全ユーザーデータを取得
 * 
 * @param obj $db PDO
 * @return array 全ユーザーデータ
 */
 function get_all_user($db){
  $sql = "
    SELECT
      user_id,
      name,
      password,
      type,
      created
    FROM
      ec_users
  ";
  return fetch_all_query($db, $sql);
 }
 

/**
 * ログインできた場合、セッション変数にユーザーIDをセット
 * 
 * @param obj $db PDO
 * @param str $name ユーザー名
 * @param str $password パスワード
 * @return array $user ユーザー配列データ
 */
function login_as($db, $name, $password){
  $user = get_user_by_name($db, $name);
  //dd($user);
  //dd($password);
  //dd($user['password']);
  //dd(password_verify($password, $user['password']));
  if($user === false || $user['password'] !== $password){
    return false;
  }
  /*
  if($user === false || password_verify($password, $user['password']) === false){
    return false;
  }*/
  
  set_session('user_id', $user['user_id']);
  return $user;
}

/**
 * ログインユーザーのデータを取得
 * 
 * ログインユーザーのユーザーIDをチェックし、
 * usersテーブルからログインユーザーのデータを取得
 * 
 * @param obj $db PDO
 * @return array ユーザーデータ配列
 */
function get_login_user($db){
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}

/**
 * ユーザーのバリデーション後、正しければパスワードをハッシュ化し、usersテーブルに書き込み
 * 
 * @param obj $db PDO
 * @param str $name ユーザー名
 * @param str $password パスワード
 * @param str $password_confirmation 確認用パスワード
 * @return bool usersデータに書き込めればtrue
 */
function regist_user($db, $name, $password, $password_confirmation) {
  if( is_valid_user($db, $name, $password, $password_confirmation) === false){
    return false;
  }
  //$password = password_hash($password, PASSWORD_DEFAULT);
  return insert_user($db, $name, $password);
}

/**
 * ログインユーザーが管理ユーザーかチェック
 * 
 * @param array $user ユーザーデータ
 * @return bool 管理ユーザーであればtrue
 */
function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

/**
 * ユーザー名とパスワードのバリデーション
 * 
 * @param str $name ユーザー名
 * @param str $password パスワード
 * @param str $password_confirmation 確認用パスワード
 * @return bool ユーザー名とパスワードが両方正しければtrue
 */
function is_valid_user($db, $name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($db, $name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

/**
 * ユーザー名のバリデーション
 * 
 * ユーザー名の文字数チェック、半角英数字チェック
 * 
 * @param str $name ユーザー名
 * @return bool $is_valid ユーザー名が正しければtrue
 */
function is_valid_user_name($db, $name) {
  $is_valid = true;
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  if(is_valid_same_user($db, $name) === false){
    set_error('既に登録されているユーザー名です。別のユーザー名でご登録ください。');
    $is_valid = false;
  }
  if(is_alphanumeric($name) === false){
    set_error('ユーザー名は半角英数字で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * パスワードのバリデーション
 * 
 * パスワードの文字数チェック、半角英数字チェック、確認用と一致するかチェック
 * 
 * @param str $password パスワード
 * @param str $password_confirmation 確認用パスワード
 * @return bool パスワードが正しいかつ確認用と一致すればtrue
 */
function is_valid_password($password, $password_confirmation){
  $is_valid = true;
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  if(is_alphanumeric($password) === false){
    set_error('パスワードは半角英数字で入力してください。');
    $is_valid = false;
  }
  if($password !== $password_confirmation){
    set_error('パスワードがパスワード(確認用)と一致しません。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * 同名ユーザーが登録されているかのバリデーション
 * 
 * @param obj $db PDO
 * @param str $name ユーザー名
 * @return bool 同名ユーザーが登録されていた場合false
 */
function is_valid_same_user($db, $name){
  $value =  select_same_user($db, $name);
  if($value['name'] === $name){
    return false;
  }
}
 

/**
 * usersテーブルにユーザーデータを書き込み
 * 
 * @param obj $db PDO
 * @param str $name ユーザー名
 * @param str $password パスワード
 * @return bool 実行できればtrue
 */
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      ec_users(name, password)
    VALUES (:name, :password);
  ";
  $params = array(':name' => $name, ':password' => $password);
  return execute_query($db, $sql, $params);
}

/**
 * 指定されたユーザー名と同じ名前のユーザーが登録されていた場合取得
 * 
 * @param obj $db pdo
 * @param str $name ユーザー名
 * @return array 同名ユーザー配列データ
 */
 function select_same_user($db, $name) {
  $sql = "
    SELECT 
      *
    FROM 
      ec_users
    WHERE 
      name = :name
    ";
  $params = array(':name' => $name);
  return fetch_query($db, $sql, $params);
}