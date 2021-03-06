<?php 
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'common.php';
// dbデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'db.php';

/**
 * ログインユーザーの購入履歴データを配列で取得
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 * @return array 結果配列データ 
 */
function get_user_histories($db, $user_id){
  $sql = "
    SELECT
      history_id,
      total_price,
      created
    FROM
      ec_purchase_histories
    WHERE
      user_id = :user_id
    ORDER BY
      created DESC
  ";
  $params = array(':user_id' => $user_id);
  return fetch_all_query($db, $sql, $params);
}

/**
 * 全ての購入履歴データを配列で取得
 * 
 * @param obj $db PDO
 * @return array 結果配列データ 
 */
function get_all_histories($db){
  $sql = "
    SELECT
      history_id,
      total_price,
      ec_purchase_histories.created,
      name
    FROM
      ec_purchase_histories
    JOIN
      ec_users
    ON
      ec_purchase_histories.user_id = ec_users.user_id
    ORDER BY
      ec_purchase_histories.created DESC
  ";
  return fetch_all_query($db, $sql);
}

/**
 * 指定の注文番号の購入明細データを配列で取得
 * 
 * @param obj $db PDO
 * @param int $history_id 注文ID
 * @return array 結果配列データ 
 */
function get_purchase_details($db, $history_id){
  $sql = "
    SELECT
      ec_purchase_details.item_id,
      ec_purchase_details.price,
      ec_purchase_details.amount,
      ec_purchase_details.sub_total,
      ec_item_master.name
    FROM
      ec_purchase_details
    JOIN
      ec_item_master
    ON
      ec_purchase_details.item_id = ec_item_master.item_id
    WHERE
      ec_purchase_details.history_id = :history_id
  ";
  $params = array(':history_id' => $history_id);
  //var_dump($params);
  return fetch_all_query($db, $sql, $params);
}

/**
 * 全ユーザーの購入商品数TOP3の商品データを配列で取得
 * 
 * @param obj $db PDO
 * @param int $history_id 注文ID
 * @return array 結果配列データ 
 */
function get_details_ranking($db){
  $sql = "
    SELECT
    SUM(ec_purchase_details.amount) AS 合計購入数,
      ec_purchase_details.item_id,
      ec_item_master.name,
      ec_item_master.price,
      ec_item_master.img,
      ec_item_master.status,
      ec_item_master.area, 
      ec_item_master.taste, 
      ec_item_master.taste_intensity, 
      ec_item_master.comment
    FROM
      ec_purchase_details
    JOIN
      ec_item_master
    ON
      ec_purchase_details.item_id = ec_item_master.item_id
    GROUP BY
      ec_purchase_details.amount,
      ec_purchase_details.item_id,
      ec_item_master.name,
      ec_item_master.price,
      ec_item_master.img
    ORDER BY
      合計購入数 DESC
    LIMIT 
      3
  ";
  return fetch_all_query($db, $sql);
}


/**
 * 購入履歴テーブルに購入履歴を登録
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 * @param int $total_price カート内商品合計金額
 * @return bool クエリ実行結果
 */
function insert_purchase_history($db, $user_id, $total_price){
  $sql = "
    INSERT INTO
      ec_purchase_histories(
        user_id,
        total_price
      )
    VALUES(:user_id, :total_price);
  ";
  $params = array(':user_id' => $user_id, ':total_price' => $total_price);
  return execute_query($db, $sql, $params);
}

/**
 * 購入明細テーブルに購入明細を登録
 * 
 * @param obj $db PDO
 * @param int $history_id 購入履歴ID
 * @param int $amount 購入数量
 * @param int $price 価格
 * @param int $sub_total 商品ごとの小計
 * @return bool クエリ実行結果
 */
function insert_purchase_detail($db, $history_id, $item_id, $amount, $price, $sub_total){
  $sql = "
    INSERT INTO
      ec_purchase_details(
        history_id,
        item_id,
        amount,
        price,
        sub_total
      )
    VALUES(:history_id, :item_id, :amount, :price, :sub_total);
  ";
  $params = array(':history_id' => $history_id, ':item_id' => $item_id, ':amount' => $amount, ':price' => $price, ':sub_total' => $sub_total);
  return execute_query($db, $sql, $params);
}

/**
 * 購入明細テーブルに購入明細を購入商品の数だけ登録
 * 
 * @param obj $db PDO
 * @param int $history_id 購入履歴ID
 * @param array $carts カート内商品データ配列
 * @return bool クエリ実行結果
 */
function for_insert_purchase_detail($db, $history_id, $carts){
  foreach($carts as $cart){
    if(insert_purchase_detail(
      $db,
      $history_id, 
      $cart['item_id'], 
      $cart['amount'], 
      $cart['price'], 
      $cart['price'] * $cart['amount']
      ) === false){
      return false;
    }
  }
  return true;
}