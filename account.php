<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'server/database/db.php';
require_once 'server/php/logger.php';

if(!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Безопасное получение постов пользователя
$sql = "SELECT * FROM posts WHERE author = ? ORDER BY timestamp DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="style/css/header.css">
    <link rel="stylesheet" href="style/css/sidebar.css">
    <link rel="stylesheet" href="style/css/account.css">
</head>
<body>
    
    <?php require 'modules/header.php' ?>

    <?php require 'modules/sidebar.php' ?>

    <main id="mainContent">
        <div class="form-container">
            <h1 class="form-title">Создание нового поста</h1>
            <form action="server/post/create_post.php" method="post">
                <div class="input-group">
                    <label>Выберите категорию:</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="classic" name="category" value="Классическая литература" checked>
                            <label for="classic">Классическая литература</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="modern" name="category" value="Современная литература">
                            <label for="modern">Современная литература</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="scifi" name="category" value="Научная фантастика">
                            <label for="scifi">Научная фантастика</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="fantasy" name="category" value="Фэнтези">
                            <label for="fantasy">Фэнтези</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="detective" name="category" value="Детективы">
                            <label for="detective">Детективы</label>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label for="title">Заголовок поста:</label>
                    <input type="text" id="title" name="title" placeholder="Введите заголовок" required>
                </div>

                <div class="input-group">
                    <label for="text_post">Текст поста:</label>
                    <textarea id="text_post" name="text_post" placeholder="Напишите ваш пост здесь..." maxlength="2000" required></textarea>
                    <div id="charCounter" class="char-counter">0/2000</div>
                </div>

                <input type="submit" value="Опубликовать пост">
            </form>
        </div>

        <div class="posts">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="container">
                        <div class="card">
                            <div class="top">
                                <div class="main-info">
                                    <h1 class="title"><?= htmlspecialchars($row['title']) ?></h1>
                                    <span class="label"><?= htmlspecialchars($row['category']) ?></span>
                                </div>
                                <p><i><?= nl2br(htmlspecialchars($row['text'])) ?></i></p>
                            </div>
                            <div class="bottom">
                                <span>Опубликовано: <em class="user"><?= htmlspecialchars($_SESSION['user']['login']) ?></em></span>
                                <span class="time_post"><?= date('d.m.Y H:i', strtotime($row['timestamp'])) ?></span>
                            </div>
                        </div>
                        <div class="tool">
                            <?php if($row['deleted'] == 0): ?>
                            <a href="server/post/edit_post.php?id=<?= $row['id'] ?>" class="edit">Редактировать</a>
                            <a href="server/post/delete_post.php?id=<?= $row['id'] ?>" class="delete">Удалить</a>
                            <?php else: ?>
                                <a href="server/post/recover_post.php?id=<?= $row['id'] ?>" class="recover">Восстановить</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-posts">
                    <p>У вас пока нет ни одного поста</p>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <script src="style/scripts/sidebar.js"></script>
    <script>
        const textarea = document.getElementById('text_post');
        const charCounter = document.getElementById('charCounter');
        const maxLength = textarea.getAttribute('maxlength');

        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCounter.textContent = `${currentLength}/${maxLength}`;
            
            if (currentLength >= maxLength) {
                charCounter.classList.add('limit-reached');
            } else {
                charCounter.classList.remove('limit-reached');
            }
        });
    </script>
</body>
</html>