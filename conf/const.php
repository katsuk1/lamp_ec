<?php
// データベースの接続情報
// MYSQLのホスト名
define('DB_HOST', 'mysql');
// MySQLのユーザー名
define ('DB_USER', 'testuser');
// MySQLのパスワード
define ('DB_PASSWD', 'password');
// MySQLのDB名
define ('DB_NAME', 'sample');
// MySQLのcharset
define ('DB_CHARSET', 'SET NAMES utf8mb4');
// MySQLの文字エンコーディング
define ('DB_CHARACTER_SET', 'utf8');
// データベースのDSN情報
define ('DSN', 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=' . DB_CHARACTER_SET);
// HTML文字エンコーディング
define ('HTML_CHARACTER_SET', 'UTF-8');


// ドキュメントルートとviewフォルダへのパス
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');
// ドキュメントルートとmodelフォルダへのパス
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');

// 画像フォルダのパス
define('IMAGE_PATH', '/assets/img/');
// CSSフォルダのパス
define('STYLESHEET_PATH', '/assets/css/');
// ドキュメントルートと画像フォルダのパス
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/img/' );

// 商品一覧ページのURL
define('ITEMLIST_URL', '/itemlist.php');
// ログインページのURL
define('LOGIN_URL', '/login.php');
// サインアップページのURL
define('SIGNUP_URL', '/signup.php');
// カートページのURL
define('CART_URL', '/cart.php');
// 商品管理ページのURL
define('ADMIN_URL', '/admin.php');
// ユーザー管理ページのURL
define('USER_ADMIN_URL', '/user_admin.php');

// 半角英数字の正規表現
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
// 正の整数の正規表現
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

// ユーザー名の最小値
define('USER_NAME_LENGTH_MIN', 6);
// ユーザー名の最大値
define('USER_NAME_LENGTH_MAX', 100);
// パスワードの最小値
define('USER_PASSWORD_LENGTH_MIN', 6);
// パスワードの最大値
define('USER_PASSWORD_LENGTH_MAX', 100);

// 管理ユーザーのユーザータイプ
define('USER_TYPE_ADMIN', 1);
// 一般ユーザーのユーザータイプ
define('USER_TYPE_NORMAL', 2);

// 商品名の最小値
define('ITEM_NAME_LENGTH_MIN', 1);
// 商品名の最大値
define('ITEM_NAME_LENGTH_MAX', 100);

// コメントの最小値
define('ITEM_COMMENT_LENGTH_MIN', 1);
// コメントの最大値
define('ITEM_COMMENT_LENGTH_MAX', 500);


// 1ページあたりの商品表示数の最大値
define('ITEMS_MAX_VIEW', 3);

// 公開ステータス
define('ITEM_STATUS_OPEN', 1);
// 非公開ステータス
define('ITEM_STATUS_CLOSE', 0);

/*
// 公開ステータスの連想配列
const PERMITTED_ITEM_STATUSES = array(
  'open' => 1,
  'close' => 0
);

// 地域の連想配列
const PERMITTED_ITEM_AREAS = array(
  '北海道',
  '東北',
  '関東',
  '中部',
  '近畿',
  '中国',
  '四国',
  '九州'
);

// 味の連想配列
const PERMITTED_ITEM_TASTES = array(
  '醤油',
  '塩',
  '味噌',
  '豚骨',
  '鶏白湯',
  '家系',
  '二郎系',
  'その他'
);

// 濃さの連想配列
const PERMITTED_ITEM_INTENSITIES = array(
  'こってり',
  '普通',
  'あっさり'
);

// 画像拡張子の連想配列
const PERMITTED_IMAGE_TYPES = array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png'
);
*/