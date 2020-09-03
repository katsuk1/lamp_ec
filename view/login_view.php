<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>ON.RAMEN トップ</title>
    <meta name="description" content="ON.RAMEN 全国行列店のラーメン通販サイト">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'login.css')); ?>">
</head>
<body>
    <div id = "home" class = "big-bg">
<?php include VIEW_PATH . 'templates/header.php'; ?>
        
        <div class="home-content">
            <h1>お家で気軽に行列店のラーメンを！</h1>
            <p class = "subcatch">行列店に並ぶなんて時間がない！恥ずかしい・・・ そんなあなたに</p>
<?php include VIEW_PATH . 'templates/messages.php'; ?>
            <form method = "post" action="login_process.php">
                <input type="text" name="name" class="text" placeholder="ユーザー名">
                <input type="password"   name="password" class="text" placeholder="パスワード">
                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                <input type="submit" class = "login"  value="ログイン">
            </form>
            <a class = "button" href = "signup.php">新規会員登録</a>
        </div>
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
</body>
</html>