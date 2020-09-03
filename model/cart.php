<?php 
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'common.php';
// dbデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'db.php';

/**
 * ログインユーザーのカートデータを配列で取得
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 * @return array 結果配列データ 
 */
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      ec_item_master.item_id,
      ec_item_master.name,
      ec_item_master.price,
      ec_item_master.status,
      ec_item_master.img,
      ec_item_stock.stock,
      ec_carts.cart_id,
      ec_carts.user_id,
      ec_carts.amount
    FROM
      ec_carts
    JOIN
      ec_item_master
    ON
      ec_carts.item_id = ec_item_master.item_id
    JOIN
      ec_item_stock
    ON
      ec_carts.item_id = ec_item_stock.item_id
    WHERE
      ec_carts.user_id = :user_id
  ";
  $params = array(':user_id' => $user_id);
  return fetch_all_query($db, $sql, $params);
}

/**
 * 指定のユーザーのカート内の指定の商品のデータを取得
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 * @param int $item_id 商品ID
 * @return array 結果配列データ 
 */
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      ec_item_master.item_id,
      ec_item_master.name,
      ec_item_master.price,
      ec_item_master.status,
      ec_item_master.img,
      ec_item_stock.stock,
      ec_carts.cart_id,
      ec_carts.user_id,
      ec_carts.amount
    FROM
      ec_carts
    JOIN
      ec_item_master
    ON
      ec_carts.item_id = ec_item_master.item_id
    JOIN
      ec_item_stock
    ON
      ec_carts.item_id = ec_item_stock.item_id
    WHERE
      ec_carts.user_id = :user_id
    AND
      ec_item_master.item_id = :item_id
  ";
  $params = array(':user_id' => $user_id, ':item_id' => $item_id);
  return fetch_query($db, $sql, $params);

}

/**
 * カートに商品を追加
 * 
 * 商品データを取得し、商品が登録されていなければcartテーブルに登録
 * 登録されていれば、購入数量を+1
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 * @param int $item_id 商品ID
 * @return bool 成功すればtrue
 */
function add_cart($db, $user_id, $item_id) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

/**
 * カートに商品を追加
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 * @param int $item_id 商品ID
 * @param int $amount 購入商品数
 * @return bool クエリ実行結果
 */
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      ec_carts(
        item_id,
        user_id,
        amount,
        create_datetime,
        update_datetime
      )
    VALUES(:item_id, :user_id, :amount, :create_datetime, :update_datetime)
  ";
  $date             = date('Y-m-d H:i:s');//年月日
  $params = array(':item_id' => $item_id, ':user_id' => $user_id, ':amount' => $amount, ':create_datetime' => $date,':update_datetime' => $date);
  return execute_query($db, $sql, $params);
}

/**
 * カート内の商品数量を更新
 * 
 * @param obj $db PDO
 * @param int $cart_id カートID
 * @param int $amount 購入数
 * @return bool 成功した場合true,失敗した場合false
 */
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      ec_carts
    SET
      amount = :amount,
      update_datetime = :update_datetime
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $date             = date('Y-m-d H:i:s');//年月日
  $params = array(':amount' => $amount, ':update_datetime' => $date, ':cart_id' => $cart_id);
  //dd($params);
  return execute_query($db, $sql, $params);
}

/**
 * 指定のカートの商品を削除
 * 
 * @param obj $db PDO
 * @param int $cart_id カートID
 * @return bool 成功すればtrue,失敗すればfalse
 */
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      ec_carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $params = array(':cart_id' => $cart_id);
  return execute_query($db, $sql, $params);
}

/**
 * カート内の商品を購入
 * 
 * 購入商品のバリデーション、itemsテーブルの在庫数更新、カート削除
 * @param obj $db PDO
 * @param array $carts カート内商品データ
 */
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
      return false;
    }
  }

  if(delete_user_carts($db, $carts[0]['user_id']) === false){
    return false;
  }
  return true;
}

/**
 * 指定のユーザーのカート内商品を全て削除
 * 
 * @param obj $db PDO
 * @param int $user_id ユーザーID
 */
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      ec_carts
    WHERE
      user_id = :user_id
  ";
  $params = array(':user_id' => $user_id);
  execute_query($db, $sql, $params);
}


/**
 * カート内商品の合計価格を算出
 * 
 * @param array $carts カート内の商品データ
 * @return int $total_price カート内商品の合計価格
 */
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

/**
 * 購入商品のバリデーション
 * 
 * 購入商品の空チェック、購入数チェック、正の整数チェック、ステータスチェック、在庫チェック、エラーチェック
 */
function validate_cart_purchase($carts){
  if(count($carts) <= 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if($cart['amount'] === 0){
      set_error($cart['name'] . 'は現在カートに入っていません。');
    }
    if(is_positive_integer($cart['amount']) === false){
      set_error($cart['name'] . 'の在庫数が正しくありません。');
    }
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}
