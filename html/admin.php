<?php
//設定ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'common.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// dbデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';
// itemデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'item.php';

//phpinfo();

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// 管理ユーザーかチェック
if(is_admin($user) === false){
  // 管理ユーザーでなければログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// 全商品データを配列で取得
$items = get_all_items($db);
// トークンを生成し、セッション変数に設定
$token = get_csrf_token();

// ビューを読み込み
include_once VIEW_PATH . 'admin_view.php';
/*
//例外処理
try {
    //DB接続
    $dbh = get_db_connect();
    
    //フォームからpostで値が送信された場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //どのフォームから送信されたかを$sql_kindに入れる
        $sql_kind = get_post_element('sql_kind');
        
        //商品追加フォームからの場合
        if ($sql_kind === 'insert') {
            
            $filename         = $_FILES['new_img']['name'];     //アップロードした画像のファイル名
            $tmp_name         = $_FILES['new_img']['tmp_name'];     //アップロードした画像の一時ファイル名
            //var_dump ($filename);
            //var_dump ($tmp_name);
            
            //名前の代入
            $name   = get_post_element('name');
            
            //価格の代入
            $price  = get_post_element('price');
            
            //個数の代入
            $stock  = get_post_element('stock');
            
            //ステータスの代入
            $status = get_post_element('status');
            
            //地域の代入
            $area   = get_post_element('area');
            
            //味の種類の代入
            $taste  = get_post_element('taste');
            
            //味の濃さの代入
            $taste_intensity = get_post_element('taste_intensity');
            
            //コメントの代入
            $comment = get_post_element('comment');
            
            //エラーチェック
            //名前のエラーチェック
            if (empty($name)) {
                $err_msg[] = '商品名を入力してください';
            } else {
                if (length_check($name, 50) === false)
                    $err_msg[] = '商品名は50文字以内で入力してください';
            }
            
            //価格のエラーチェック
            if (empty($price)) {
                $err_msg[] = '価格を入力してください';
            } else if (preg_match($int_regex, $price) === 0) {
                $err_msg[] = '価格は半角数字で入力してください';
            } else {
                if (length_check($price, 5) === false)
                    $err_msg[] = '価格は5桁以内で入力してください';
            }
            
            //在庫数のエラーチェック
            if (empty_check($stock) === false) {
                $err_msg[] = '在庫数を入力してください';
            } else if (preg_match($int_regex, $stock) === 0) {
                $err_msg[] = '在庫数は半角数字で入力してください';
            } else {
                if (length_check($stock, 10) === false)
                    $err_msg[] = '在庫数は10桁以内で入力してください';
            }
            
            //ステータスのエラーチェック
            if (empty_check($status) === false) {
                $err_msg[] = 'ステータスを入力してください';
            } else if ($status !== '0' && $status !== '1') {
                $err_msg[] = 'ステータスが正しくありません';
            }
            
            //地域のエラーチェック
            if (empty($area)) {
                $err_msg[] = '地域を入力してください';
            } else {
                if (length_check($area, 50) === false)
                    $err_msg[] = '地域は50文字以内で入力してください';
            }
            
            //味の種類のエラーチェック
            if (empty($taste)) {
                $err_msg[] = '味の種類を入力してください';
            } else {
                if (length_check($taste, 10) === false)
                    $err_msg[] = '味の種類は10文字以内で入力してください';
            }
            
            //味の濃さのエラーチェック
            if (empty($taste_intensity)) {
                $err_msg[] = '味の濃さを入力してください';
            } else {
                if (length_check($taste_intensity, 10) === false)
                    $err_msg[] = '味の濃さは10文字以内で入力してください';
            }
            
            //コメントのエラーチェック
            if (empty($comment)) {
                $err_msg[] = 'コメントを入力してください';
            } else {
                if (length_check($comment, 500) === false)
                    $err_msg[] = 'コメントは500文字以内で入力してください';
            }
            
            //画像ファイルアップロード
            //ファイルがアップロードされたかチェック
            if (is_uploaded_file($tmp_name) === TRUE) {
                //画像の拡張子を取得
                $extension = get_ext($filename);
                //var_dump ($extension);
                //指定の拡張子かどうかチェック
                if (in_array(strtolower($extension), $perm, true)) {
                    //保存する新しいユニークなファイル名の生成
                    $new_img_filename = get_uniq_filename($extension);
                    //var_dump ($new_img_filename);
                    //同名ファイルが存在するかチェック
                    if(samename_check($img_dir,$new_img_filename) !== TRUE) {
                        if (move_uploaded_file($tmp_name, $img_dir . $new_img_filename) !== TRUE) {
                            $err_msg[] = 'ファイルアップロードに失敗しました';
                        }
                    } else {
                        $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
                    }
                } else {
                    $err_msg[] = 'ファイル形式が異なります。画像ファイルはjpeg,jpg,pngのみ利用可能です。';
                }
            } else {
                $err_msg[] = 'ファイルを選択してください';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                
                //トランザクション開始
                $dbh -> beginTransaction();
                
                try {
                    var_dump($new_img_filename);
                    //drink_masterテーブルへの書き込み
                    insert_item_master($dbh, $name, $price, $new_img_filename, $status, $area, $taste, $taste_intensity, $comment, $date);
                    
                    //drink_masterに書き込んだdrink_idを取得
                    $item_id = $dbh->lastinsertid();
                    //var_dump($drink_id);
                    
                    //drink_stockテーブルへの書き込み
                    insert_item_stock($dbh, $item_id, $stock, $date);
                    
                    //コミット処理
                    $dbh -> commit();
                    $msg = 'データ登録ができました';
                
                } catch (PDOException $e) {
                    //ロールバック処理
                    $dbh -> rollback();
                    throw $e;
                }
                
            }
        
        }
        
        //画像更新のフォームからの場合
        if ($sql_kind === 'update_img') {
            
            //hiddenのitem_id
            $item_id = get_post_element('item_id');
            
            $filename         = $_FILES['new_img']['name'];     //アップロードした画像のファイル名
            $tmp_name         = $_FILES['new_img']['tmp_name'];     //アップロードした画像の一時ファイル名
            //var_dump ($filename);
            //var_dump ($tmp_name);
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //画像ファイルアップロード
            //ファイルがアップロードされたかチェック
            if (is_uploaded_file($tmp_name) === TRUE) {
                //画像の拡張子を取得
                $extension = get_ext($filename);
                //var_dump ($extension);
                //指定の拡張子かどうかチェック
                if (in_array(strtolower($extension), $perm, true)) {
                    //保存する新しいユニークなファイル名の生成
                    $new_img_filename = get_uniq_filename($extension);
                    //var_dump ($new_img_filename);
                    //同名ファイルが存在するかチェック
                    if(samename_check($img_dir,$new_img_filename) !== TRUE) {
                        if (move_uploaded_file($tmp_name, $img_dir . $new_img_filename) !== TRUE) {
                            $err_msg[] = 'ファイルアップロードに失敗しました';
                        }
                    } else {
                        $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
                    }
                } else {
                    $err_msg[] = 'ファイル形式が異なります。画像ファイルはjpeg,jpg,pngのみ利用可能です。';
                }
            } else {
                $err_msg[] = 'ファイルを選択してください';
            }
            
            if (count($err_msg) === 0) {
                //画像の更新
                update_img($dbh, $new_img_filename, $date, $item_id);
                $msg = '画像が変更されました';
            }
            
        }
        
        //商品名更新のフォームからの場合
        if ($sql_kind === 'update_name') {
            
            //商品名を代入
            $name    = get_post_element('name');
            
            //hiddenで渡されたitem_idを代入
            $item_id = get_post_element('item_id');
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //名前のエラーチェック
            if (empty($name)) {
                $err_msg[] = '商品名を入力してください';
            } else {
                if (length_check($name, 50) === false)
                    $err_msg[] = '商品名は50文字以内で入力してください';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                //商品名の更新
                update_name($dbh, $name, $date, $item_id);
                $msg = '商品名が変更されました';
            }
            
        }
        
        //価格更新のフォームからの場合
        if ($sql_kind === 'update_price') {
            
            //価格の代入
            $price = get_post_element('price');
            
            //hiddenで渡されたitem_idを代入
            $item_id = get_post_element('item_id');
            
            //価格のエラーチェック
            if (empty($price)) {
                $err_msg[] = '価格を入力してください';
            } else if (preg_match($int_regex, $price) === 0) {
                $err_msg[] = '価格は半角数字で入力してください';
            } else {
                if (length_check($price, 5) === false)
                    $err_msg[] = '価格は5桁以内で入力してください';
            }
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                //在庫数の更新
                update_price($dbh, $price, $date, $item_id);
                $msg = '価格が変更されました';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                //商品名の更新
                update_price($dbh, $price, $date, $item_id);
                $msg = '価格が変更されました';
            }
            
        }
        //在庫更新のフォームからの場合
        if ($sql_kind === 'update_stock') {
            
            //個数の代入
            $stock = get_post_element('stock');
            
            //hiddenで渡されたitem_idを代入
            $item_id = get_post_element('item_id');
            
            //在庫数のエラーチェック
            if (empty_check($stock) === false) {
                $err_msg[] = '在庫数を入力してください';
            } else if (preg_match($int_regex, $stock) === 0) {
                $err_msg[] = '在庫数は半角数字で入力してください';
            } else {
                if (length_check($stock, 10) === false)
                $err_msg[] = '在庫数は10桁以内で入力してください';
            }
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                //在庫数の更新
                update_stock($dbh, $stock, $date, $item_id);
                $msg = '在庫数が変更されました';
            }
            
        }
        
        //ステータス更新のフォームからの場合
        if ($sql_kind === 'update_status') {
            
            //hiddenで渡されたstatusを代入
            $status = get_post_element('status');
            
            //hiddenで渡されたitem_idを代入
            $item_id = get_post_element('item_id');
            
            //ステータス変更
            if ($status === '1') {
                $status = '0';
            } else {
                $status = '1';
            }
            
            //ステータスのエラーチェック
            if (empty_check($status) === false) {
                $err_msg[] = 'ステータスを入力してください';
            } else if ($status !== '0' && $status !== '1') {
                $err_msg[] = 'ステータスが正しくありません';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                //在庫数の更新
                update_status($dbh, $status, $date, $item_id);
                $msg = 'ステータスが変更されました';
            }
            
        }
        
        //コメント更新のフォームからの場合
        if ($sql_kind === 'update_comment') {
            
            //コメントを代入
            $comment = get_post_element('comment');
            
            //hiddenで渡されたitem_idを代入
            $item_id = get_post_element('item_id');
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //コメントのエラーチェック
            if (empty($comment)) {
                $err_msg[] = 'コメントを入力してください';
            } else {
                if (length_check($comment, 500) === false)
                    $err_msg[] = 'コメントは500文字以内で入力してください';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                //コメントの更新
                update_comment($dbh, $comment, $date, $item_id);
                $msg = 'コメントが変更されました';
            }
            
        }
        
        //削除更新のフォームからの場合
        if ($sql_kind === 'delete') {
            
            //hiddenで渡されたitem_idを代入
            $item_id = get_post_element('item_id');
            
            //item_idのエラーチェック
            if (empty($item_id)) {
                $err_msg[] = '商品が選択されていません';
            } else if (preg_match($int_regex, $item_id) === 0) {
                $err_msg[] = '商品が正しく選択されていません';
            } else {
                if (length_check($item_id, 10) === false)
                $err_msg[] = '商品が正しくありません';
            }
            
            //エラーチェック後エラーがなければ
            if (count($err_msg) === 0) {
                
                //トランザクション開始
                $dbh -> beginTransaction();
                
                try {
                
                //ec_item_masterテーブルのレコード削除
                delete_master($dbh, $item_id);
                
                //ec_item_stockテーブルのレコード削除
                delete_stock($dbh, $item_id);
                
                //コミット処理
                    $dbh -> commit();
                    $msg = '商品が削除されました';
                
                } catch (PDOException $e) {
                    //ロールバック処理
                    $dbh -> rollback();
                    throw $e;
                }
                
            }
            
        }
            
    }
    
    //DBからデータを取得
    $data = get_data($dbh, $data);

} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:' . $e->getMessage();
}
*/
