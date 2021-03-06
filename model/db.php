<?php
/**
* DBハンドルを取得
* @return obj $dbh DBハンドル
*/
function get_db_connect() {
  try {
    // データベースに接続(PDOクラスのインスタンスを作成)
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => DB_CHARSET));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //print 'データベースに接続できました';
    } catch (PDOException $e) {
      exit('接続できませんでした。理由：'.$e->getMessage() );
    }
    return $dbh;
}

/**
 * 指定されたクエリを実行し、配列を1行取得
 * 
 * @param obj $db PDO
 * @param str $sql 実行するクエリ
 * @param array $params 空の配列
 * @return array 結果配列データ
 */
function fetch_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。理由：'.$e->getMessage() );
  }
  return false;
}

/**
 * 指定されたクエリを実行し、全行配列で取得
 * 
 * @param obj $db PDO
 * @param str $sql 実行するクエリ
 * @param array $params 空の配列
 * @return array 結果配列データ
 */
function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。理由：'.$e->getMessage() );
  }
  return false;
}

/**
 * クエリを実行
 * 
 * @param obj $db PDO
 * @param str $sql クエリ
 * @param array $params 空の配列
 * return クエリ実行結果
 */
function execute_query($db, $sql, $params = array()){
  try{
    //dd($params);
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。理由：'.$e->getMessage() );
  }
  return false;
}