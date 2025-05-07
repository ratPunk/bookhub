<!-- бургер меню -->
<div class="burger-menu" id="burgerMenu">
        <div class="burger-line"></div>
        <div class="burger-line"></div>
        <div class="burger-line"></div>
    </div>
    <!-- бургер меню -->

    <!-- сайдбар -->
    <div class="sidebar" id="sidebar">

        <h3>Аккаунт</h3>
        <hr>
        <div class="side-buttons">
        <?php if (isset($_SESSION['user'])): ?>
                <a href="account.php" class="button_system account">
                    <span><?php echo $_SESSION['user']['login']; ?></span>
                </a>
            <?php else: ?>
                <a href="auth/login_form.html" class="button_system login">
                    <span>Войти</span>
                </a>
            <?php endif; ?>
        </div>
        <hr>

        <h3>Категории</h3>
        <hr>
        <div class="side-category">
            <button class="side-category-btn" data-category="Классическая литература">Классическая литература</button>
            <button class="side-category-btn" data-category="Современная литература">Современная литература</button>
            <button class="side-category-btn" data-category="Научная фантастика">Научная фантастика</button>
            <button class="side-category-btn" data-category="Фэнтези">Фэнтези</button>
            <button class="side-category-btn" data-category="Детективы">Детективы</button>
        </div>
        <hr>
            
        <h3>Дополнительно</h3>
        <hr>    
        <nav>
            <ul>
                <li><a href="#">О нас</a></li>
                <li><a href="#">Услуги</a></li>
                <li><a href="#">Портфолио</a></li>
                <li><a href="#">Контакты</a></li>
            </ul>
        </nav>
        <hr>
        
        <?php if (isset($_SESSION['user'])): ?>
            <a href="server/php/logout.php" class="button_system logout">
                <span>Выйти</span>
            </a>
        <?php endif; ?>
    </div>
    <!-- сайдбар -->