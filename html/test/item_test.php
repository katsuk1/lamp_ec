<?php
//設定ファイル読み込み
require_once '../../conf/const.php';
// userデータに関する関数ファイルの読み込み
require MODEL_PATH . 'user.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'common.php';
// DBに関する関数ファイルを読み込み
require_once MODEL_PATH . 'db.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

$db = get_db_connect();

// 指定の商品データを取得
$items = get_item($db, 1);
if (isset($items) === true) {
  echo 'OK';
  //var_dump($items);
} else {
  echo 'NG';
}

echo '<br>';

// 全商品データを取得
$items = get_all_items($db);
if (isset($items) === true) {
  echo 'OK';
  //var_dump($items);
} else {
  echo 'NG';
}

echo '<br>';

// 公開ステータスの商品データを定数に応じた数取得
$items = get_open_items($db);
if (isset($items) === true) {
  echo 'OK';
  //var_dump($items);
} else {
  echo 'NG';
}

echo '<br>';

// 指定の商品データを取得
$items = get_item($db, 1);
if (isset($items) === true) {
  echo 'OK';
  //var_dump($items);
} else {
  echo 'NG';
}

echo '<br>';