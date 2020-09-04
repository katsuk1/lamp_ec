<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'purchase_detail.css')); ?>">
    <title>購入明細</title>
</head>
<body>
    <div id = "home" class = "big-bg">
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

        <div class="container">
            <article>
                <div class="title">
                    <h1>購入明細</h1>
                </div>
<?php include VIEW_PATH . 'templates/messages.php'; ?>
    
<?php if(count($details) > 0){ ?>
                <div class="table-wrapper">
                    <table class="table table-bordered">
                        <thead class="thead">
                            <tr>
                                <th>注文番号</th>
                                <td><?php print (h($history_id)); ?></td>
                                <th>購入日時</th>
                                <td><?php print (h($created)); ?></td>
                                <th>合計金額</th>
                                <td><?php print (h(number_format($total_price))); ?>円</td>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-striped">
                        <thead class="thead">
                            <tr>
                                <th>商品名</th>
                                <th>商品価格</th>
                                <th>購入数</th>
                                <th>小計</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach($details as $detail){ ?>
                            <tr>
                                <td><?php print (h($detail['name'])); ?></td>
                                <td><?php print (h(number_format($detail['price']))); ?>円</td>
                                <td><?php print (h($detail['amount'])); ?></td>
                                <td><?php print (h(number_format($detail['sub_total']))); ?>円</td>
                            </tr>
<?php } ?>
                        </tbody>
                    </table>
<?php } else { ?>
                    <p>購入明細はありません</p>
<?php } ?> 
                </div>
            </article>
        </div>
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
</body>
</html>