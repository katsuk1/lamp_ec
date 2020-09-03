<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang = "ja">
    <head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
<link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'user_admin.css')); ?>">
        <title>ユーザー管理ページ</title>
    </head>
    <body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>

        
<?php include VIEW_PATH . 'templates/messages.php'; ?>
        <div class = "container">
            <h1 class="h1 my-3">ユーザー管理ページ</h1>
<?php if(count($users) > 0){ ?>
            <h2 class="h2">ユーザー情報一覧</h2>
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>ユーザー名</th>
                        <th>登録日時</th>
                        <th>タイプ</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach($users as $user) { ?>
                    <tr>
                        <td><?php print h($user['name']); ?></td>
                        <td><?php print h($user['created']); ?></td>
                        <td><?php print h($user['type']); ?></td>
<?php } ?>
                    </tr>
                </tbody>
            </table>
<?php } else { ?>
            <p>ユーザーはいません。</p>
<?php } ?> 
        </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
    </body>
</html>