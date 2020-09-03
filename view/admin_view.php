<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
<link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'admin.css')); ?>">
    <title>商品管理ページ</title>
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    
    <div class="container">
        <h1 class="h1 my-3">商品管理ページ</h1>
<?php include VIEW_PATH . 'templates/messages.php'; ?>
        <div class = "wrapper">
            <h2 class="h2">新規商品追加</h2>
            <form method="post" action="admin_insert_item.php" enctype="multipart/form-data" class="add_item_form col-md-6">
                <div class="form-group">
                    <label for="name">名前: </label>
                    <input class="form-control" type="text" name="name" id="name">
                </div>
                <div class="form-group">
                    <label for="price">値段: </label>
                    <input class="form-control" type="text" name="price" id="price">
                </div>
                <div class="form-group">
                    <label for="stock">個数: </label>
                    <input class="form-control" type="text" name="stock" id="stock">
                </div>
                <div class="form-group">
                    <label for="new_img">商品画像: </label>
                    <input class="form-control" type="file" name="new_img" id="new_img">
                </div>
                <div class="form-group">
                    <label for="status">ステータス: </label>
                    <select class="form-control" name = "status" id="status">
                        <option value = "close">非公開</option>
                        <option value = "open">公開</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="area">地域: </label>
                    <select class="form-control" name = "area" id="area">
                        <option value = "北海道">北海道</option>
                        <option value = "東北">東北</option>
                        <option value = "関東">関東</option>
                        <option value = "中部">中部</option>
                        <option value = "近畿">近畿</option>
                        <option value = "中国">中国</option>
                        <option value = "四国">四国</option>
                        <option value = "九州">九州</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="taste">味: </label>
                    <select class="form-control" name = "taste" id="taste">
                        <option value = "醤油">醤油</option>
                        <option value = "塩">塩</option>
                        <option value = "味噌">味噌</option>
                        <option value = "豚骨">豚骨</option>
                        <option value = "鶏白湯">鶏白湯</option>
                        <option value = "家系">家系</option>
                        <option value = "二郎系">二郎系</option>
                        <option value = "その他">その他</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="taste_intensity">濃さ: </label>
                    <select class="form-control" name = "taste_intensity" id="taste_intensity">
                        <option value = "こってり">こってり</option>
                        <option value = "普通">普通</option>
                        <option value = "あっさり">あっさり</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">コメント: </label>
                        <textarea class="form-control" name="comment" id="comment"></textarea>
                </div>
                <input type="hidden" name = "csrf_token" value="<?php print $token;?>" >
                <div>
                    <input type="submit" value="商品追加" class="btn btn-primary">
                </div>
            </form>
        </div>
        
<?php if(count($items) > 0){ ?>
        
        <p class="h3">商品一覧</p>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>ステータス</th>
                    <th>地域</th>
                    <th>味の種類</th>
                    <th>味の濃さ</th>
                    <th>コメント</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($items as $item) { ?>
                <tr class="<?php print (h(is_open($item)? '' : 'close_item')); ?>">
                    <td>
                        <form method="post" action="admin_change_img.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <img class="item_image" src= "<?php print h(IMAGE_PATH . $item['img']); ?>">
                                <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                                <input type="file"   name="new_img" class="btn btn-primary">
                            </div>
                            <input type="submit" value="画像を変更" class="btn btn-secondary">
                        </form>
                    </td>
                    <td>
                        <form method="post" action="admin_change_name.php">
                            <div class="form-group">
                                <input type="text" name ="name" value = "<?php print h($item['name']); ?>">
                                <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                            </div>
                            <input type="submit" value="商品名を変更" class="btn btn-secondary">
                        </form>
                    </td>
                    <td>
                        <form method="post" action="admin_change_price.php">
                            <div class="form-group">
                                <input type="text"   name="price"    class="text" value="<?php print h($item['price']); ?>">円&nbsp;&nbsp;
                                <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                            </div>
                            <input type="submit" value="価格を変更" class="btn btn-secondary">
                        </form>
                    </td>
                    <td>
                        <form method="post" action="admin_change_stock.php">
                            <div class="form-group">
                                <input type="text"   name="stock"    class="text" value="<?php print h($item['stock']); ?>">個&nbsp;&nbsp;
                                <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                            </div>
                            <input type="submit" value="在庫数を変更" class="btn btn-secondary">
                        </form>
                    </td>
                    <td>
                        <form method="post" action="admin_change_status.php" class="operation">
<?php if (is_open($item) === true) { ?>
                            <input type="submit" value="公開→非公開" class="btn btn-secondary">
                            <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                            <input type="hidden" name="changes_to" value="close">
<?php } else { ?>
                            <input type="submit" value="非公開→公開" class="btn btn-secondary">
                            <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                            <input type="hidden" name="changes_to" value="open">
<?php } ?>
                            <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                        </form>
                    </td>
                    <td><?php print h($item['area']); ?></td>
                    <td><?php print h($item['taste']); ?></td>
                    <td><?php print h($item['taste_intensity']); ?></td>
                    <td>
                        <form method = 'post' action="admin_change_comment.php">
                            <div class="form-group">
                                <textarea name="comment"><?php print h($item['comment']); ?></textarea>
                                <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                            </div>
                            <input type="submit" value="コメントを変更" class="btn btn-secondary">
                        </form>
                    </td>
                    <td>
                        <form method="post" action="admin_delete_item.php">
                            <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                            <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                            <input type="submit" value="削除する" class="btn btn-danger delete">
                        </form>
                    </td>
<?php } ?>
                </tr>
            </tbody>
        </table>
<?php } else { ?>
        <p>商品はありません。</p>
<?php } ?> 
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
<script>
    $('.delete').on('click', () => confirm('本当に削除しますか？'))
</script>
</body>
</html>