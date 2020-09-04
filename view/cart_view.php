<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>ON.RAMEN カート情報</title>
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'cart.css')); ?>">
</head>
<body>
    <div id = "home" class = "big-bg">
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <div class="cart">
            <article>
                <div class="title">
                    <h1>カート情報</h1>
                </div>
                <div>
<?php include VIEW_PATH . 'templates/messages.php'; ?>
                    <div class="item-wrapper">
<?php foreach ($carts as $cart) { ?>
                        <div class="item">
                            <p><?php print h($cart['name']); ?></p>
                            <div class = "img">
                                <img src = "<?php print h(IMAGE_PATH . $cart['img']); ?>">
                            </div>
                            <p>価格:<?php print h($cart['price']); ?>円(税込)</p>
                            <form method="post" action="cart_change_amount.php">
                                <input type="number" class="number" name="amount" value="<?php print h($cart['amount']); ?>">個&nbsp;&nbsp;
                                <input type="hidden" name="cart_id" value="<?php print h($cart['cart_id']); ?>">
                                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                                <input type="submit" value="数量を変更" class="btn btn-primary">
                            </form>
                            <form method = "post" action="cart_delete_cart.php">
                                <input type="hidden" name="cart_id" value = "<?php print h($cart['cart_id']); ?>">
                                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                                <input type="submit" value="削除" class = "delete">
                            </form>
                        </div>
<?php } ?>
                    </div>
                    <div class="buy-sum-box">
                        <p>合計:<?php print h(number_format($total_price)); ?>円(税込)</p>
                    </div>
<?php if (!empty($carts)) { ?>
                    <form method = "post" class = "buy" action = "./result.php">
                        <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                        <input type="submit" value="購入">
                    </form>
<?php } ?>
                </div>
            </article>
        </div>
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
    <script>
        $('.delete').on('click', () => confirm('本当に削除しますか？'))
    </script>
</body>
</html>