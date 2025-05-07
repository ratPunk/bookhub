<header>
        <div class="logo">
        <a href="index.php" class="logo-link"><h1>BookHub</h1></a>
        </div>

        <div class="buttons">
            <nav>
                <a href="../../../forum2/index.php">Главная</a>
                <a href="#">О нас</a>
                <a href="../../../forum2/author.html">Автор</a>
                <a href="#">Обсуждения</a>
            </nav>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="../../../forum2/account.php" class="button_system">
                    <span><?php echo $_SESSION['user']['login']; ?></span>
                </a>
                <a href="server/php/logout.php" class="button_system">
                    <span>Выйти</span>
                </a>
            <?php else: ?>
                <a href="auth/login_form.html" class="button_system">
                    <span>Войти</span>
                </a>
            <?php endif; ?>
        </div>
    </header>