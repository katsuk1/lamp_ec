<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>ON.RAMEN 商品一覧</title>
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'itemlist.css')); ?>">
    <script>
    $(function(){
        var nowchecked = $('input[name=search_area]:checked').val();
        $('input[name=search_area]').click(function(){
            if($(this).val() == nowchecked) {
                $(this).prop('checked', false);
                nowchecked = false;
            } else {
                nowchecked = $(this).val();
            }
        });
        nowchecked = $('input[name=search_taste]:checked').val();
        $('input[name=search_taste]').click(function(){
            if($(this).val() == nowchecked) {
                $(this).prop('checked', false);
                nowchecked = false;
            } else {
                nowchecked = $(this).val();
            }
        });
        nowchecked = $('input[name=search_intensity]:checked').val();
        $('input[name=search_intensity]').click(function(){
            if($(this).val() == nowchecked) {
                $(this).prop('checked', false);
                nowchecked = false;
            } else {
                nowchecked = $(this).val();
            }
        });
    });
</script>
</head>
<body>
    <div id = "home" class = "big-bg">
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <div class="itemlist-contents wrapper">
            <aside>
                <div class="conditions">
                    <h1>条件を絞り込む</h1>
                </div>
                <div class = "conditions">
                    <h2>地域</h2>
                    <div>
                        <form method = "post">
                            <ul>
                                <li><input type="radio" name="search_area" value="北海道" <?php if($search_area === '北海道'){ print h('checked'); } ?>>北海道</li>
                                <li><input type="radio" name="search_area" value="東北" <?php if($search_area === '東北'){ print h('checked'); } ?>>東北</li>
                                <li><input type="radio" name="search_area" value="関東" <?php if($search_area === '関東'){ print h('checked'); } ?>>関東</li>
                                <li><input type="radio" name="search_area" value="中部" <?php if($search_area === '中部'){ print h('checked'); } ?>>中部</li>
                                <li><input type="radio" name="search_area" value="近畿" <?php if($search_area === '近畿'){ print h('checked'); } ?>>近畿</li>
                                <li><input type="radio" name="search_area" value="中国" <?php if($search_area === '中国'){ print h('checked'); } ?>>中国</li>
                                <li><input type="radio" name="search_area" value="四国" <?php if($search_area === '四国'){ print h('checked'); } ?>>四国</li>
                                <li><input type="radio" name="search_area" value="九州" <?php if($search_area === '九州'){ print h('checked'); } ?>>九州</li>
                            </ul>
                    </div>
                    <h2>味</h2>
                    <div>
                            <ul>
                                <li><input type="radio" name="search_taste" value="醤油" <?php if($search_taste === '醤油'){ print h('checked'); } ?>>醤油</li>
                                <li><input type="radio" name="search_taste" value="塩" <?php if($search_taste === '塩'){ print h('checked'); } ?>>塩</li>
                                <li><input type="radio" name="search_taste" value="味噌" <?php if($search_taste === '味噌'){ print h('checked'); } ?>>味噌</li>
                                <li><input type="radio" name="search_taste" value="豚骨" <?php if($search_taste === '豚骨'){ print h('checked'); } ?>>豚骨</li>
                                <li><input type="radio" name="search_taste" value="鶏白湯" <?php if($search_taste === '鶏白湯'){ print h('checked'); } ?>>鶏白湯</li>
                                <li><input type="radio" name="search_taste" value="家系" <?php if($search_taste === '家系'){ print h('checked'); } ?>>家系</li>
                                <li><input type="radio" name="search_taste" value="二郎系" <?php if($search_taste === '二郎系'){ print h('checked'); } ?>>二郎系</li>
                                <li><input type="radio" name="search_taste" value="その他" <?php if($search_taste === 'その他'){ print h('checked'); } ?>>その他</li>
                            </ul>
                    </div>
                    <h2>濃さ</h2>
                    <div>
                            <ul>
                                <li><input type="radio" name="search_intensity" value="こってり" <?php if($search_intensity === 'こってり'){ print h('checked'); } ?>>こってり</li>
                                <li><input type="radio" name="search_intensity" value="普通" <?php if($search_intensity === '普通'){ print h('checked'); } ?>>普通</li>
                                <li><input type="radio" name="search_intensity" value="あっさり" <?php if($search_intensity === 'あっさり'){ print h('checked'); } ?>>あっさり</li>
                            </ul>
                    </div>
                            <input type="submit" value="検索" class="btn btn-primary">
                            <input type="hidden" name = "form_kind" value="search_category">
                            <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                        </form>
                    
                    
                    
                </div>
            </aside>
            <article>
                <div class="title">
                    <h1>商品一覧</h1>
                    <form method = "post">
                        <input type="text" name="search_text" value="<?php print h($search_text); ?>" placeholder = "商品名から検索" class="text">
                        <input type="submit" value="検索" class="btn btn-primary">
                        <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                    </form>
                </div>
<?php 
if($items_num['num'] > 0){
    if(isset($items)){
        if($now == $pages_num){
            print ('<p>全件数' . (h($items_num['num'])) . '件中' . (h($start + 1)) . '〜' . (h($items_num['num'])) . '件目の商品'); 
        }else{
            print ('<p>全件数' . (h($items_num['num'])) . '件中' . (h($start + 1)) . '〜' . (h($start + ITEMS_MAX_VIEW)) . '件目の商品'); 
        }
    }
}else{
    print ('<p>検索条件に該当する商品がありません。</p>'); 
}
?>
<?php include VIEW_PATH . 'templates/messages.php'; ?>
                <div class="item-wrapper">
<?php foreach ($items as $item) { ?>
                    <div class="item">
                        <p><?php print  h($item['name']); ?></p>
                        <div class = "img">
                            <img src = <?php print h(IMAGE_PATH . $item['img']); ?>>
                        </div>
                        <p><?php print h(number_format($item['price'])); ?>円(税込)</p>
<?php if ($item['stock'] === 0) { ?>
                    <span class = "red">売り切れ</span>
<?php } else { ?>
                        <form method = "post" action = "itemlist_add_cart.php">
                            <input class = "button" type="submit" value="カートに入れる" >
                            <input type="hidden" name="item_id" value = "<?php print h($item['item_id']); ?>">
                            <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                        </form>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal<?php print $item['item_id']; ?>">詳細</button>
                            <div id="modal<?php print $item['item_id']; ?>" class="modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-primary"><!-- ヘッダー -->
                                            <h5 class="modal-title"><?php print h($item['name']); ?></h5>
                                            <button class="btn btn-secondary" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body border-primary">
                                            
                                            <div class="card">
                                                <img class="card-img-top" src ="<?php print IMAGE_PATH . $item['img']; ?>" alt = "商品画像" >
                                                <div class="card-body bg-primary">
                                                    <p class="card-text">特徴<br><?php print h($item['comment']) ?></p>
                                                </div>
                                            </div>
                                            <p class="text-left">価格:<?php print h($item['price']); ?>円(税込)</p>
                                            <p class="text-left">地域:<?php print h($item['area']); ?></p>
                                            <p class="text-left">味:<?php print h($item['taste']); ?></p>
                                            <p class="text-left">濃さ:<?php print h($item['taste_intensity']); ?></p>
                                            
                                            
                                        </div>
                                        <div class="modal-footer border-primary"><!-- フッター -->
                                            <button class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

<?php } ?>
                    </div>
<?php } ?>
                </div>
                <nav aria-label="Page Navigation" class="my-3">
                  <ul class="pagination justify-content-center">
                    <?php if($now > 1){ ?>
                      <li class="page-item">
                        <a  class="page-link" href="./itemlist.php?page=<?php print (h($now - 1)); ?>" aria-label="Previous Page">
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>
                    <?php } ?>
                    <?php
                      for($i=1; $i <= $pages_num; $i++){
                        if($i == $now){
                          print ('<li class="page-item active"><a class="page-link" href="#">' . $now . '</a></li>');
                        }else{
                          print ('<li class="page-item"><a class="page-link" href="' . './itemlist.php?page=' . $i . '">' . $i . '</a></li>');
                        }
                      }
                    ?>
                    <?php if($pages_num > $now){ ?>
                      <li class="page-item">
                        <a  class="page-link" href="./itemlist.php?page=<?php print (h($now + 1)); ?>" aria-label="Next Page">
                          <span aria-hidden="true">&raquo;</span>
                        </a>
                      </li>
                    <?php } ?>
                  </ul>
                </nav>
            </article>
        </div>
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
</body>
</html>