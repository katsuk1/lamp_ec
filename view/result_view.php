<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>ON.RAMEN 購入内容</title>
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'result.css')); ?>">
</head>
<body>
    <div id = "home" class = "big-bg">
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <div class="result">
            <article>
                <div class="title">
                    <h1>購入内容</h1>
                </div>

<?php include VIEW_PATH . 'templates/messages.php'; ?>
            <p>ご購入ありがとうございました。またのご利用をお待ちしております。</p>
                <div class="item-wrapper">
<?php if(count($carts) > 0){ ?>
                    <table class="table table-borderless">
                        <thead class="thead">
                            <tr>
                                <th>商品画像</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>購入数</th>
                                <th>小計</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach($carts as $cart){ ?>
                            <tr>
                                <td><img src="<?php print (h(IMAGE_PATH . $cart['img']));?>"></td>
                                <td class=item-name><?php print (h($cart['name'])); ?></td>
                                <td><?php print (h(number_format($cart['price']))); ?>円</td>
                                <td><?php print (h($cart['amount'])); ?>個</td>
                                <td><?php print (h(number_format($cart['price'] * $cart['amount']))); ?>円</td>
                            </tr>
<?php } ?>
                        </tbody>
                    </table>


                    <div class="buy-sum-box">
                        <p>合計:<?php print h(number_format($total_price)); ?>円(税込)</span>
                    </div>
                    <a href = "./itemlist.php">商品一覧に戻る</a>
<?php } else { ?>
                    <p>カートに商品はありません。</p>
<?php } ?> 
                </div>
                
            </article>
        </div>
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
</body>
</html>