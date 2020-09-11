<?php
//設定ファイル読み込み
require_once '../../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'common.php';

// セッション変数をセット
set_session('user_id', 1);
if ($_SESSION['user_id'] === 1) {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// セッション変数を取得
$user_id = get_session('user_id');
if ($user_id === 1) {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 指定したセッション変数を削除
isset_delete_session('user_id');
if (!isset($_SESSION['user_id'])){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// セッションにエラー文を保存
set_error('エラー文');
if($_SESSION['__errors'][0] === 'エラー文'){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// セッションにセットされているエラー文を配列で取得しセッションから削除
$error = get_errors();
//var_dump($error);
if($error[0] === 'エラー文' && empty($_SESSION['__errors'])){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// セッション変数にエラーがセットされていないかチェック

if (has_error() === false){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// セッションにメッセージをセット
set_message('メッセージ');
if ($_SESSION['__messages'][0] === 'メッセージ'){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// セッションにセットされているメッセージを取得し、セッションから削除
$messages = get_messages();
//var_dump($_SESSION['__messages']);
if($messages[0] === 'メッセージ' && empty($_SESSION['__messages'])){
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// 20文字のランダムな文字列生成
$str = get_random_string();
if (mb_strlen($str) === 20) {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

    // 指定の文字数のランダムな文字列生成
    $str = get_random_string(46);
    //var_dump($str);
    if (mb_strlen($str) === 46) {
      echo 'OK';
    } else {
      echo 'NG';
    }

echo "<br>";

// 文字列の長さのバリデーション
if (is_valid_length('アイウエオ', 2, 10) === true) {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

    // 文字列の長さが超えている場合
    if (is_valid_length('アイウエオカキクケコサシスセソ', 2, 10) === false) {
      echo 'OK';
    } else {
      echo 'NG';
    }

echo "<br>";

// 半角英数字のバリデーション
if (is_alphanumeric('a1hodd34') === true) {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

    // 半角英数字以外が含まれている場合
    if (is_alphanumeric('12ai@*あい') === false) {
      echo 'OK';
    } else {
      echo 'NG';
    }

    echo "<br>";

// 正の整数のバリデーション
if (is_positive_integer(345457765) === true) {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

    // 正の整数ではない場合
    if (is_positive_integer(-100) === false) {
      echo 'OK';
    } else {
      echo 'NG';
    }

echo "<br>";

  // 正の整数ではない場合
  if (is_positive_integer(100.5) === false) {
    echo 'OK';
  } else {
    echo 'NG';
  }

  echo "<br>";

// エスケープ処理
$str = '<p>';
if (h($str) === '&lt;p&gt;') {
  echo 'OK';
} else {
  echo 'NG';
}

echo "<br>";

// ワイルドカードのエスケープ
if (escape_wildcard('%') === '\%' && escape_wildcard('\\') === '\\\\' && escape_wildcard('_') === '\_') {
  echo 'OK';
} else {
  echo 'NG';
}