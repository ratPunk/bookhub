<u?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHub</title>
    <link rel="stylesheet" href="style/css/styles.css">
</head>
<body>

    <header>

        <div class="logo">
            <h1>BookHub</h1>
        </div>

        <div class="buttons">
            <nav>
                <a href="index.php" class="active">Главная</a>
                <a href="#">Категории</a>
                <a href="#">Авторы</a>
                <a href="#">Обсуждения</a>
            </nav>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="account.php" class="button_system">
                    <span ><?php echo $_SESSION['login']; ?></span>
                </a>
                <a href="server/php/logout.php" class="button_system">
                    <span>Выйти</span>
                </a>
            <?php else: ?>
                <a href="login.html" class="button_system">
                    <span>Войти</span>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div class="category">
        <button class="category-btn" data-category="Классическая литература">Классическая литература</button>
        <button class="category-btn" data-category="Современная литература">Современная литература</button>
        <button class="category-btn" data-category="Научная фантастика">Научная фантастика</button>
        <button class="category-btn" data-category="Фэнтези">Фэнтези</button>
        <button class="category-btn" data-category="Детективы">Детективы</button>
    </div>

    <main>
        <div class="container">
            <div class="card">
                <div class="top">
                    <div class="main-info">
                        <h1 class="title">Евгений Онегин</h1>
                        <u class="label">Классическая литература</u>
                    </div>
                    <p><i>Описание поста, разбор произведения с точки зрения покупки гватемалы Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore, nam illum non voluptatem neque modi necessitatibus sint consequatur expedita animi magni atque rem inventore obcaecati beatae architecto odio, accusantium maiores? Nemo voluptatem dolor enim. Distinctio quos omnis delectus, commodi rerum culpa molestias pariatur! Asperiores omnis voluptatibus itaque, ducimus pariatur est quas illum rem, nam possimus ad accusamus vero! Porro dolore autem blanditiis quas, delectus sequi omnis quia error, molestiae aperiam dolorem, quidem natus sapiente? Ipsum, minus corporis. At suscipit similique vero deleniti et commodi inventore incidunt quaerat! Veniam, expedita dolores explicabo aliquid similique quae, minima quasi corporis quod cumque quam quia quibusdam hic laboriosam dolorem vel id? Delectus repellendus omnis deserunt error quam molestias ullam placeat non eos a magnam nam hic, pariatur consequatur laudantium, sequi expedita, officia quas enim quos ratione? Suscipit deleniti autem repellendus cupiditate sed dolorem expedita blanditiis consectetur odio esse enim minus maxime sint impedit atque culpa, fugiat sunt doloribus quam asperiores nisi? Repellendus sapiente nesciunt ratione facere sint assumenda qui, fugit cupiditate numquam aut recusandae? Obcaecati, iste earum! Quibusdam voluptatem natus consequatur eligendi illo voluptatum, ducimus laudantium voluptas, eaque nam, ratione iste. Dolor repudiandae perspiciatis rerum nulla, adipisci magnam! Error expedita cupiditate officiis culpa et mollitia sapiente quae ut maiores unde vero atque, praesentium facere odio nemo vitae sed corporis dolorem quas nesciunt. Vero praesentium nostrum accusantium ducimus consectetur earum eligendi, nisi, odio quasi minus incidunt ipsam enim nobis voluptatibus consequatur ab. Cupiditate optio cum voluptatem fugiat amet ut aliquid a quaerat ipsum ullam ab iusto nemo culpa voluptate fuga soluta qui dolorem, totam, est dignissimos. Sint voluptates obcaecati, adipisci, eius nobis impedit culpa id repellendus distinctio debitis est voluptatibus reiciendis a? Quia earum dolorum, sit, mollitia, eius consectetur perspiciatis repellat sequi totam provident expedita! Libero aliquam distinctio delectus perferendis recusandae optio dicta dolore et.</i></p>
                </div>

                <div class="bottom">
                    <span>Опубликовано: <em class="user">Александр Данилин</em></span>
                    <u class="time_post">12.05.2025 | 16:53:21</u>
                </div>
            </div>
            <div class="card">text</div>
            <div class="card">text</div>
            <div class="card">text</div>
            <div class="card">text</div>
        </div>
    </main>

</body>
</html>