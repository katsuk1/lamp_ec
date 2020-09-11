<?php
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'common.php';
// dbデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'db.php';

/**
 * 指定の商品の商品データを取得
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @return array 商品データ配列
 */
function get_item($db, $item_id){
  $sql = "
    SELECT
      ec_item_master.item_id, 
      name,
      price,
      img,
      status,
      area, 
      taste, 
      taste_intensity, 
      comment,
      stock
    FROM
      ec_item_master
    JOIN 
      ec_item_stock
    ON 
    ec_item_master.item_id = ec_item_stock.item_id
    WHERE
      ec_item_master.item_id = :item_id
  ";
  $params = array(':item_id' => $item_id);
  return fetch_query($db, $sql, $params);
}

/**
 * 商品データ全てorステータスが公開の商品データを全て取得
 * 
 * $is_openにtrueを渡せばステータスが公開の商品を取得
 * $startを渡せば$startから指定の件数ずつ取得
 * 
 * @param obj $db PDO
 * @param bool $is_open フラグ
 * @param int $start データ取得開始位置
 * @return array 結果配列データ
 */
function get_items($db, $is_open = false, $start = ''){
  $sql = '
    SELECT
      ec_item_master.item_id, 
      ec_item_master.name,
      ec_item_master.price,
      ec_item_master.img,
      ec_item_master.status,
      ec_item_master.area, 
      ec_item_master.taste, 
      ec_item_master.taste_intensity, 
      ec_item_master.comment,
      ec_item_stock.stock
    FROM
      ec_item_master
    JOIN 
      ec_item_stock
    ON 
      ec_item_master.item_id = ec_item_stock.item_id
  ';
  $params = array();
  if($is_open === true){
    $sql .= '
      WHERE ec_item_master.status = 1
      LIMIT
        :start,
        :max
    ';
    $params = array(':start' => $start, ':max' => ITEMS_MAX_VIEW);
  }
  return fetch_all_query($db, $sql, $params);
}

/**
 * 商品データを全て取得
 * 
 * @param obj $db PDO
 * @return array 商品データ配列
 */
function get_all_items($db){
  return get_items($db);
}

/**
 * ステータスが公開の商品データを配列で取得
 * 
 * @param obj $db PDO
 * @param int 
 * @return array 結果配列データ
 */
function get_open_items($db, $start = ''){
  return get_items($db, true, $start);
}


/**
 * 商品テーブルの公開商品のレコード数を取得
 * 
 * @param obj $db PDO
 * @return array itemsテーブルのレコード数
 */
function count_items_records($db, $is_search = false, $search_text = ''){
  $sql = "
    SELECT
      COUNT(*) as 'num'
    FROM
      ec_item_master
    WHERE
      status = 1
  ";
  $params = array();
  if($is_search === true){
    $sql .= '
      AND
        name LIKE :search_text
    ';
    $query = '%' . $search_text . '%';
    $params = array(':search_text' => $query);
  }
  return fetch_query($db, $sql, $params);
}

/**
 * 検索条件に当てはまる公開商品のレコード数を取得
 * 
 * @param obj $db PDO
 * @return array 検索条件に当てはまる公開商品のレコード数
 */
function count_items_search_records($db, $search_text){
  return count_items_records($db, true, $search_text);
}

/**
 * 商品テーブルの公開商品のレコード数を取得
 * 
 * @param obj $db PDO
 * @return array itemsテーブルのレコード数
 */
function count_items_records_category($db, $search_area = '', $search_taste = '', $search_intensity = ''){
  $sql = "
    SELECT
      COUNT(*) as 'num'
    FROM
      ec_item_master
    WHERE
      status = 1
    ";
    $params = array();
    if(!empty($search_area)){
      $sql .= '
      AND
        area = :search_area
      ';
      $params = array(':search_area' => $search_area);
    }
    if(!empty($search_taste)){
      $sql .= '
      AND
        taste = :search_taste
      ';
      if(empty($params)){
        $params = array(':search_taste' => $search_taste);
      }else{
        $params += array(':search_taste' => $search_taste);
      }
    }
    if(!empty($search_intensity)){
      $sql .= '
      AND
        taste_intensity = :search_intensity
      ';
      if(empty($params)){
        $params = array(':search_intensity' => $search_intensity);
      }
        $params += array(':search_intensity' => $search_intensity);
    }
  //$params = array(':search_area' => $search_area, ':search_taste' => $search_taste, ':search_intensity' => $search_intensity);
  return fetch_query($db, $sql, $params);
}

/**
 * 商品一覧のトータルページ数を取得
 * 
 * @param obj $db PDO
 * @return int トータルページ数
 */
function get_pages_num($db, $items_num){
  // トータルページ数を取得
  return ceil($items_num['num'] / ITEMS_MAX_VIEW);
}

/**
 * 商品一覧の現在のページ数を取得
 * 
 * @return int $now 商品一覧の現在のページ数
 */
function get_now_page(){
  // 現在のページ数を取得($_GET['page_id']はURLに渡された現在のページ数)
  if(!isset($_GET['page'])){ 
    // 設定されていない場合は1ページ目とする
    $now = 1;
  }else{
    $now = $_GET['page'];
  }
  return $now;
}

/**
 * 商品データ取得の開始位置を取得
 * 
 */
function get_limit_start($now){
  return ($now - 1) * ITEMS_MAX_VIEW;
}

/**
 * ページネーション
 * 
 * @param obj $db PDO
 * @return array 商品データ配列
 */
function pagenation($db){
  // 現在のページ数を取得($_GET['page_id']はURLに渡された現在のページ数)
  $now = get_now_page();
  //var_dump($now);
  $start = get_limit_start($now);
  //var_dump($start);
  return get_open_items($db, $start);
}

/**
 * キーワード検索
 * 
 * 
 * 
 * 
 */
function get_search_text($db, $search_text, $start = ''){
  $sql = "
    SELECT 
      * 
    FROM 
      ec_item_master 
    JOIN 
     ec_item_stock
    ON 
      ec_item_master.item_id = ec_item_stock.item_id
    WHERE 
      name LIKE :search_text
    AND 
      ec_item_master.status = 1
    LIMIT
      :start,
      :max
  ";
  $query = '%' . $search_text . '%';
  $params = array(':search_text' => $query, ':start' => $start, ':max' => ITEMS_MAX_VIEW);
  return fetch_all_query($db, $sql, $params);
}


/**
 * カテゴリー検索
 * 
 * 
 */
function get_search_category($db, $search_area, $search_taste, $search_intensity, $start = ''){
  $sql = "
    SELECT 
      * 
    FROM 
      ec_item_master 
    JOIN 
     ec_item_stock
    ON 
      ec_item_master.item_id = ec_item_stock.item_id
    WHERE 
      ec_item_master.status = 1
    ";
    $params = array();
    if(!empty($search_area)){
      $sql .= '
      AND
        area = :search_area
      ';
      $params = array(':search_area' => $search_area);
    }
    if(!empty($search_taste)){
      $sql .= '
      AND
        taste = :search_taste
      ';
      if(empty($params)){
        $params = array(':search_taste' => $search_taste);
      }else{
        $params += array(':search_taste' => $search_taste);
      }
    }
    if(!empty($search_intensity)){
      $sql .= '
      AND
        taste_intensity = :search_intensity
      ';
      if(empty($params)){
        $params = array(':search_intensity' => $search_intensity);
      }
        $params += array(':search_intensity' => $search_intensity);
    }
    $sql .= '
    LIMIT
      :start,
      :max
    ';
  $params += array(':start' => $start, ':max' => ITEMS_MAX_VIEW);
  return fetch_all_query($db, $sql, $params);
}

/**
 * カテゴリ検索条件に当てはまる商品のページネーション 
 * 
 */
function search_category_pagenation($db, $search_area = '', $search_taste = '', $search_intensity = ''){
  // 現在のページ数を取得($_GET['page_id']はURLに渡された現在のページ数)
  $now = get_now_page();
  //var_dump($now);
  $start = get_limit_start($now);
  
  return get_search_category($db, $search_area, $search_taste, $search_intensity, $start);
}

/**
 * 検索条件に当てはまる商品のページネーション 
 * 
 */
function search_text_pagenation($db, $search_text = ''){
  // 現在のページ数を取得($_GET['page_id']はURLに渡された現在のページ数)
  $now = get_now_page();
  //var_dump($now);
  $start = get_limit_start($now);
  
  return get_search_text($db, $search_text, $start);
}

/**
 * ランダムな画像ファイル名を取得し、商品の各バリデーション後、itemsテーブルに登録、画像保存
 * 
 * @param obj $db PDO
 * @param str $name 商品名
 * @param int $price 価格
 * @param int $stock 在庫数
 * @param str $status ステータス
 * @param str $image アップロードされた画像ファイル名
 * @param str $filename 生成したランダムなファイル名
 * @return bool 成功すればtrue
 */
function regist_item($db, $name, $price, $stock, $status, $image, $area, $taste, $intensity, $comment){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status, $area, $taste, $intensity, $comment) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename, $area, $taste, $intensity, $comment);
}

/**
 * ec_item_masterテーブルとec_item_stockテーブルへの商品データ書き込みと画像ファイル保存のトランザクション
 * 
 * @param obj $db PDO
 * @param str $name 商品名
 * @param int $price 価格
 * @param int $stock 在庫数
 * @param str $status ステータス
 * @param str $image アップロードされた画像ファイル名
 * @param str $filename 生成したランダムなファイル名
 * @return bool 成功すればtrue
 */
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename, $area, $taste, $intensity, $comment){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $filename, $status, $area, $taste, $intensity, $comment) 
  && save_image($image, $filename) === false){
    $db->rollback();
    return false;
  }
  $item_id = get_last_insert_id($db);
  if(insert_item_stock($db, $item_id, $stock) === false){
    $db->rollback();
    return false;
  }
  $db->commit();
  return true;
  
}

/**
 * 画像の更新処理
 * 
 * ランダムな画像ファイル名を取得し、画像のバリデーション後、テーブルに登録、画像を保存
 * 
 * @param obj $db PDO
 * @param str $image ファイル名
 * @param int $item_id 商品ID
 */
 function regist_item_image($db, $image, $item_id){
   $filename = get_upload_filename($image);
   if(is_valid_item_filename($filename) === false){
     return false;
   }
   if(update_item_image($db, $item_id, $filename) && save_image($image, $filename) === false){
     return false;
   }
  return true;
 }
 
 /**
 * 商品名の更新処理
 * 
 * 商品名のバリデーション後、商品名を更新
 * 
 * @param obj $db PDO
 * @param str $name 商品名
 * @param int $item_id 商品ID
 */
 function regist_item_name($db, $name, $item_id){
   if(is_valid_item_name($name) === false){
     return false;
   }
   if(update_item_name($db, $item_id, $name) === false){
     return false;
   }
  return true;
 }
 
/**
 * 価格の更新処理
 * 
 * 価格のバリデーション後、価格を更新
 * 
 * @param obj $db PDO
 * @param int $price 価格
 * @param int $item_id 商品ID
 */
 function regist_item_price($db, $price, $item_id){
   if(is_valid_item_price($price) === false){
     return false;
   }
   if(update_item_price($db, $item_id, $price) === false){
     return false;
   }
  return true;
 }

/**
 * 在庫数の更新処理
 * 
 * 在庫数のバリデーション後、在庫数を更新
 * 
 * @param obj $db PDO
 * @param int $stock 在庫数
 * @param int $item_id 商品ID
 */
 function regist_item_stock($db, $stock, $item_id){
   if(is_valid_item_stock($stock) === false){
     return false;
   }
   if(update_item_stock($db, $item_id, $stock) === false){
     return false;
   }
  return true;
 }
 
/**
 * コメントの更新処理
 * 
 * コメントのバリデーション後、コメントを更新
 * 
 * @param obj $db PDO
 * @param str $comment コメント
 * @param int $item_id 商品ID
 */
 function regist_item_comment($db, $comment, $item_id){
   if(is_valid_item_comment($comment) === false){
     return false;
   }
   if(update_item_comment($db, $item_id, $comment) === false){
     return false;
   }
  return true;
 }
 
 
/**
 * ec_item_masterテーブルに商品を登録
 * 
 * @param obj $db PDO
 * @param str $name 商品名
 * @param int $price 価格
 * @param int $stock 在庫数
 * @param str $status ステータス
 * @param str $filename 生成したランダムなファイル名
 * @return bool 成功すればtrue
 */
function insert_item($db, $name, $price, $filename, $status, $area, $taste, $intensity, $comment){
  $is_valid_status = array(
    'open' => 1,
    'close' => 0
  );
  $status_value = $is_valid_status[$status];
  $sql = "
    INSERT INTO
      ec_item_master(
        name,
        price,
        img,
        status,
        area,
        taste,
        taste_intensity,
        comment,
        create_datetime,
        update_datetime
      )
    VALUES(:name, :price, :filename, :status_value, :area, :taste, :taste_intensity, :comment, :create_datetime, :update_datetime);
  ";
  $date             = date('Y-m-d H:i:s');//年月日
  $params = array(':name' => $name, ':price' => $price, ':filename' => $filename, ':status_value' => $status_value, ':area' => $area, ':taste' => $taste, ':taste_intensity' => $intensity, ':comment' => $comment, ':create_datetime' => $date, ':update_datetime' => $date);
  //dd($params);
  return execute_query($db, $sql, $params);
}

/**
 * ec_item_stockテーブルに在庫数を登録
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @param int $stock 在庫数
 */
function insert_item_stock($db, $item_id, $stock){
  $sql = "
    INSERT INTO
      ec_item_stock(
        item_id,
        stock,
        create_datetime,
        update_datetime
      )
    VALUES(:item_id, :stock, :create_datetime, :update_datetime);
  ";
  $date             = date('Y-m-d H:i:s');//年月日
  $params = array(':item_id' => $item_id, ':stock' => $stock, ':create_datetime' => $date, 'update_datetime' => $date);
  //dd($params);
  return execute_query($db, $sql, $params);
}
  
/**
 * 商品画像を更新
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @param str $filename 生成したランダムなファイル名
 * @return bool 成功すればtrue
 */
 function update_item_image($db, $item_id, $filename){
    $sql = "
    UPDATE
      ec_item_master
    SET
      img = :filename
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':filename' => $filename, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}
 
 /**
 * 商品名を更新
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @param str $name 商品名
 * @return bool 成功すればtrue
 */
 function update_item_name($db, $item_id, $name){
    $sql = "
    UPDATE
      ec_item_master
    SET
      name = :name
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':name' => $name, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

 /**
 * 価格を更新
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @param int $price 価格
 * @return bool 成功すればtrue
 */
 function update_item_price($db, $item_id, $price){
    $sql = "
    UPDATE
      ec_item_master
    SET
      price = :price
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':price' => $price, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

/**
 * 商品のステータスを更新
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @param int ステータス
 * @return bool 成功すればtrue
 */
function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      ec_item_master
    SET
      status = :status
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':status' => $status, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

/**
 * 在庫数を更新
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @param int $stock 在庫数
 * @return bool 成功すればtrue,失敗すればfalse
 */
function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      ec_item_stock
    SET
      stock = :stock
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':stock' => $stock, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

 /**
 * コメントを更新
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @param str $comment コメント
 * @return bool 成功すればtrue
 */
 function update_item_comment($db, $item_id, $comment){
    $sql = "
    UPDATE
      ec_item_master
    SET
      comment = :comment
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':comment' => $comment, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

/**
 * 商品データ、画像の削除のトランザクション
 * 
 * 指定の商品データを取得し、商品データと画像フォルダの画像ファイルを削除
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @return bool コミットに成功すればtrue
 */
function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['img'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

/**
 * itemsテーブルから指定の商品データを削除
 * 
 * @param obj $db PDO
 * @param int $item_id 商品ID
 * @return bool 削除に成功すればtrue
 */
function delete_item($db, $item_id){
  $sql = "
    DELETE 
      ec_item_master, ec_item_stock
    FROM
      ec_item_master
    JOIN
      ec_item_stock
    ON
      ec_item_master.item_id = ec_item_stock.item_id
    WHERE
      ec_item_master.item_id = :item_id
  ";
  $params = array(':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

// 非DB

/**
 * ステータスが公開かチェック
 * 
 * @param $item 商品データ
 * @return bool 公開であればtrue
 */
function is_open($item){
  return $item['status'] === 1;
}

/**
 * 商品名、価格、在庫数、ファイル名、ステータスのバリデーション
 * 
 * @param str $name 商品名
 * @param int $price 価格
 * @param int $stock 在庫数
 * @param str $filename ファイル名
 * @param int $status 公開ステータス
 * @return bool 全部正しければtrue
 */
function validate_item($name, $price, $stock, $filename, $status, $area, $taste, $intensity, $comment){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);
  $is_valid_item_area = is_valid_item_area($area);
  $is_valid_item_taste = is_valid_item_taste($taste);
  $is_valid_item_intensity = is_valid_item_intensity($intensity);
  $is_valid_item_comment = is_valid_item_comment($comment);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status
    && $is_valid_item_area
    && $is_valid_item_taste
    && $is_valid_item_intensity
    && $is_valid_item_comment;
}

/**
 * 商品名のバリデーション
 * 
 * 商品名の文字列チェック
 * 
 * @param str $name 商品名
 * @return bool $is_valid 正しければtrue
 */
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * 価格のバリデーション
 * 
 * 整数の正規表現にマッチするかチェック
 * 
 * @param int $price 商品価格
 * @return bool $is_valid 正の整数であればtrue
 */
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * 在庫数のバリデーション
 * 
 * 整数の正規表現にマッチするかチェック
 * 
 * @param int $stock 在庫数
 * @return bool $is_valid 正の整数であればtrue
 */
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * ファイル名のバリデーション
 * 
 * ファイルが空かチェック
 * 
 * @param str $filename ファイル名
 * @return bool $is_valid 空でなければtrue
 */
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * 公開ステータスのバリデーション
 * 
 * 存在するか、存在した場合openまたはcloseかチェック
 * @param str $status 公開ステータス
 * @return bool $is_valid 正しければtrue
 */
function is_valid_item_status($status){
  $is_valid = true;
  $is_valid_status = array(
      'open' => 1,
      'close' => 0
    );
  if(array_key_exists($status, $is_valid_status) === false){
    set_error('ステータスの選択が正しくありません。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * 地域のバリデーション
 * 
 * 存在するか、存在した場合指定の地域に該当するかチェック
 * @param str $area 地域
 * @return bool $is_valid 正しければtrue
 */
 function is_valid_item_area($area){
   $is_valid = true;
   $is_valid_area = array(
      '北海道',
      '東北',
      '関東',
      '中部',
      '近畿',
      '中国',
      '四国',
      '九州'
    );
   if(in_array($area, $is_valid_area, true) === false){
     set_error('地域の選択が正しくありません。');
      $is_valid = false;
   }
   return $is_valid;
 }
 
 /**
 * 味のバリデーション
 * 
 * 存在するか、存在した場合指定の　味に該当するかチェック
 * @param str $taste 味
 * @return bool $is_valid 正しければtrue
 */
 function is_valid_item_taste($taste){
   $is_valid = true;
   $is_valid_taste = array(
      '醤油',
      '塩',
      '味噌',
      '豚骨',
      '鶏白湯',
      '家系',
      '二郎系',
      'その他'
    );
   if(in_array($taste, $is_valid_taste, true) === false){
     set_error('味の選択が正しくありません。');
      $is_valid = false;
   }
   return $is_valid;
 }
 
 /**
 * 濃さのバリデーション
 * 
 * 存在するか、存在した場合指定の濃さに該当するかチェック
 * @param str $intensity 濃さ
 * @return bool $is_valid 正しければtrue
 */
 function is_valid_item_intensity($intensity){
   $is_valid = true;
   $is_valid_intensity = array('こってり', '普通', 'あっさり');
   if(in_array($intensity, $is_valid_intensity, true) === false){
     set_error('濃さの選択が正しくありません。');
      $is_valid = false;
   }
   return $is_valid;
 }
 
 /**
 * コメントのバリデーション
 * 
 * コメントの文字列チェック
 * 
 * @param str $name 商品名
 * @return bool $is_valid 正しければtrue
 */
function is_valid_item_comment($comment){
  $is_valid = true;
  if(is_valid_length($comment, ITEM_COMMENT_LENGTH_MIN, ITEM_COMMENT_LENGTH_MAX) === false){
    set_error('コメントは'. ITEM_COMMENT_LENGTH_MIN . '文字以上、' . ITEM_COMMENT_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}