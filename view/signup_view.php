<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>ON.RAMEN 新規会員登録</title>
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'header.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'footer.css')); ?>">
    <link rel="stylesheet" href="<?php print (h(STYLESHEET_PATH . 'signup.css')); ?>">
    </head>
<body>
    <div id = "home" class = "big-bg">
<?php include VIEW_PATH . 'templates/header.php'; ?>
        
        <div class="home-content wrapper">
<?php include VIEW_PATH . 'templates/messages.php'; ?>
            <form method = "post" action="signup_process.php">
                <input type="text"   name="name" class="text" placeholder="ユーザー名">
                <input type="password" name="password" class="text" placeholder="パスワード">
                <input type="password" name="password_confirmation" class="text" placeholder="パスワード(確認用)">
                <p class = "red">※パスワードは半角英数字6文字以上で設定してください。</p>
                <input type="submit" class = "register"  value="新規会員登録">
                <input type="hidden" name = "csrf_token" value="<?php print $token;?>">
                <p class = "blue">すでにアカウントをお持ちですか？ </p>
                    <a href = "./login.php" class = "back-login">ログインページへ</a>
                
            </form>
        </div>
    </div>
<?php include VIEW_PATH . 'templates/footer.php'; ?>
</body>
</html>