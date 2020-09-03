<?php
//設定ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'common.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';
// itemデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'item.php';

// 検索条件の変数を初期化
$search_text = '';
// 地域の検索条件の変数を初期化
$search_area = '';
// 味の検索条件の変数を初期化
$search_taste = '';
// 濃さの検索条件の変数を初期化
$search_intensity = '';

//セッション開始
session_start();

//ログインチェック
if (is_logined() === false) {
    //ログインしていない場合ログインページへリダイレクト
    redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// 検索フォームからpostされた場合
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    // hiddenで送信されたトークンを取得
    $token = get_post('csrf_token');
    // トークンのチェック
    if(is_valid_csrf_token($token) === false){
      // 正しくなければログインページへリダイレクト
      redirect_to(LOGIN_URL);
    }
    // セッション変数に設定したトークンを削除
    unset($_SESSION['csrf_token']);
    
    // フォームの種類を取得
    $form_kind = get_post('form_kind');
    
    // 検索された場合現在のページ数を1に戻す
    $now = 1;
    
    // セッションの検索条件を削除
    isset_delete_session('search_area');
    isset_delete_session('search_taste');
    isset_delete_session('search_intensity');
    
    // フォーム種類がカテゴリ検索だった場合
    if($form_kind === 'search_category'){
        
        // 検索条件(テキスト)がセッションに保存されていれば
        isset_delete_session('search_text');
        
        // 検索条件(地域)がpostされた場合、セッションに検索条件(地域)を保存
        set_session_post('search_area');
        // 検索条件(味)がpostされた場合、セッションに検索条件(味)を保存
        set_session_post('search_taste');
        // 検索条件(濃さ)がpostされた場合、セッションに検索条件(濃さ)を保存
        set_session_post('search_intensity');
        
        // セッションから検索条件(地域)を取得
        $search_area = get_session('search_area');
        // セッションから検索条件(味)を取得
        $search_taste = get_session('search_taste');
        // セッションから検索条件(濃さ)を取得
        $search_intensity = get_session('search_intensity');
        
        // 検索条件に当てはまる公開商品のレコード数を取得
        $items_num = count_items_records_category($db, $search_area, $search_taste, $search_intensity);
        
        // トータルページ数を取得
        $pages_num = get_pages_num($db, $items_num);
        
        // 商品データ取得の開始位置を取得
        $start = get_limit_start($now);
        
        // 検索条件に当てはまる商品データを取得
        $items = search_category_pagenation($db, $search_area, $search_taste, $search_intensity);
    
    // テキスト検索の場合
    }else{
        
        // セッションに検索条件(テキスト)を保存
        set_session_post('search_text');
        
        // セッションから検索条件(テキスト)を取得
        $search_text = get_session('search_text');
        // ワイルドカードをエスケープ
        $search_text = escape_wildcard($search_text);
        
        // 検索条件に当てはまる公開商品のレコード数を取得
        $items_num = count_items_search_records($db, $search_text);
        
        // トータルページ数を取得
        $pages_num = get_pages_num($db, $items_num);
        
        // 商品データ取得の開始位置を取得
        $start = get_limit_start($now);
        
        // 検索条件に当てはまる商品データを取得
        $items = search_text_pagenation($db, $search_text);
    }

// ログイン時orページネーション
}else{
    
    // 現在のページ数を取得
    $now = get_now_page();
    
    // セッションに検索条件が保存されている場合
    if(isset($_SESSION['search_text'])){
        // セッションから検索条件を取得
        $search_text = get_session('search_text');
        // ワイルドカードをエスケープ
        $search_text = escape_wildcard($search_text);
        // 検索条件に当てはまる公開商品のレコード数を取得
        $items_num = count_items_search_records($db, $search_text);
    
    // セッションに検索条件(地域、味、濃さ)が保存されている場合
    }else if(isset($_SESSION['search_area']) || isset($_SESSION['search_taste']) || isset($_SESSION['search_intensity'])){
        // セッションから検索条件(地域)を取得
        $search_area = get_session('search_area');
        // セッションから検索条件(味)を取得
        $search_taste = get_session('search_taste');
        // セッションから検索条件(濃さ)を取得
        $search_intensity = get_session('search_intensity');
        // 検索条件に当てはまる公開商品のレコード数を取得
        $items_num = count_items_records_category($db, $search_area, $search_taste, $search_intensity);
        //var_dump($items_num);
    
    // セッションに検索条件が保存されていない場合
    }else{
        // 全公開商品のレコード数を取得
        $items_num = count_items_records($db);
    }
    
    // トータルページ数を取得
    $pages_num = get_pages_num($db, $items_num);
    
    // 商品データ取得の開始位置を取得
    $start = get_limit_start($now);
    
    // セッションに検索条件(テキスト)が保存されている場合
    if(isset($_SESSION['search_text'])) {
        // 検索条件(テキスト)に当てはまる公開商品データを取得
        $items = search_text_pagenation($db, $search_text);
    
    // セッションに検索条件(地域、味、濃さ)が保存されている場合
    }else if(isset($_SESSION['search_area']) || isset($_SESSION['search_taste']) || isset($_SESSION['search_intensity'])){
        // 検索条件に当てはまる商品データを取得
        $items = search_category_pagenation($db, $search_area, $search_taste, $search_intensity);
    
    // セッションに検索条件が保存されていない場合
    }else{
        // 全公開商品データを取得
        $items = pagenation($db);
        
    }

}

// トークンを生成し、セッション変数に設定
$token = get_csrf_token();

// ビューの読み込み
include_once VIEW_PATH . 'itemlist_view.php';