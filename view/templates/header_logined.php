<header>
    <div class = "w-75 mx-auto nav-wrapper">
        <nav class = "navbar navbar-expand-md">
            <a class="navbar-brand" href = "./login.php">
                <img src = "<?php print h(IMAGE_PATH); ?>logo-9.png" alt = "ON.RAMEN トップ" class="site-logo">
            </a>
            <button class="navbar-toggler navbar-dark" data-toggle="collapse" data-target="#headerNav" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="ナビゲーションの切替">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="headerNav">
                <ul class = "navbar-nav">
                    <li class="nav-item">
                        <a class = "underline nav-link" href = "./itemlist.php">商品一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class = "underline nav-link" href = "./history.php">購入履歴</a>
                    </li>
                    <li class="nav-item">
                        <a class = "underline nav-link" href = "./logout.php">ログアウト</a>
                    </li>
                    
<?php if(is_admin($user)){ ?>
                    <li class="nav-item">
                        <a class="underline nav-link" href="<?php print (h(ADMIN_URL));?>">管理</a>
                    </li>
                     <li class="nav-item">
                        <a class="underline nav-link" href="<?php print (h(USER_ADMIN_URL));?>">ユーザー管理</a>
                    </li>
<?php } ?>
                    <li class="nav-item">
                        <a class = "underline nav-link" href = "./cart.php">
                            <img class = "cart-icon" src = "<?php print h(IMAGE_PATH); ?>cart5.png" alt = "カート">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <p class = "pb-4 pl-2">ようこそ、<?php print (h($user['name'])); ?>さん。</p>
    </div>
</header>