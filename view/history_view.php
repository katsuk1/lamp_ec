<!DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>ON.RAMEN 購入履歴</title>
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'history.css')); ?>">
</head>
<body>
    <div id = "home" class = "big-bg">
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <div class="history">
            <article>
                <div class="title">
                    <h1>購入履歴</h1>
                </div>
<?php include VIEW_PATH . 'templates/messages.php'; ?>

                <div class="history-wrapper">
<?php if(count($histories) > 0){ ?>
                    <table class="table table-striped">
                        <thead class="thead">
                            <tr>
                                <th>注文番号</th>
<?php if(is_admin($user) === true){print '<th>ユーザー名</th>';} ?>
                                <th>購入日時</th>
                                <th>合計金額</th>
                                <th>明細</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach($histories as $history){ ?>
                            <tr>
                                <td><?php print (h($history['history_id'])); ?></td>
<?php if(is_admin($user) === true){print '<td>' . (h($history['name'])) . '</td>';} ?>
                                <td><?php print (h($history['created'])); ?></td>
                                <td><?php print (h(number_format($history['total_price']))); ?>円</td>
                                <td class="text-center">
                                    <form action="purchase_detail.php" method="post">
                                        <input type="submit" value="購入明細表示" class="btn btn-primary">
                                        <input type="hidden" name="csrf_token" value="<?php print $token;?>">
                                        <input type="hidden" name="history_id" value="<?php print (h($history['history_id'])); ?>">
                                        <input type="hidden" name="created" value="<?php print (h($history['created'])); ?>">
                                        <input type="hidden" name="total_price" value="<?php print (h($history['total_price'])); ?>">
                                    </form>
                                </td>
                            </tr>
<?php } ?>
                        </tbody>
                    </table>
<?php } else { ?>
                <p>購入履歴はまだありません。</p>
<?php } ?> 
                </div>
            </article>
        </div>
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
</body>
</html>