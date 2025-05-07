<?php
session_start();
require_once 'server/php/logger.php';
require_once 'server/database/db.php';

Logger::log("Загрузка страницы с постами", "INFO");

// Основной запрос с подзапросом для получения логина автора
$sql = "SELECT 
            p.*, 
            (SELECT login FROM users WHERE id = p.author) as author_login 
        FROM posts p 
        WHERE p.deleted = 0 
        ORDER BY p.timestamp DESC";

$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHub</title>
    <link rel="stylesheet" href="style/css/header.css">
    <link rel="stylesheet" href="style/css/styles.css">
    <link rel="stylesheet" href="style/css/sidebar.css">
</head>
<body>

    <?php require 'modules/header.php' ?>

    <?php require 'modules/sidebar.php' ?>



    <main id="mainContent">

    <div class="category">
        <button class="category-btn" data-category="Классическая литература">Классическая литература</button>
        <button class="category-btn" data-category="Современная литература">Современная литература</button>
        <button class="category-btn" data-category="Научная фантастика">Научная фантастика</button>
        <button class="category-btn" data-category="Фэнтези">Фэнтези</button>
        <button class="category-btn" data-category="Детективы">Детективы</button>
    </div>

    <div class="container">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="top">
                        <div class="main-info">
                            <h1 class="title"><?= htmlspecialchars($row['title']) ?></h1>
                            <u class="label"><?= htmlspecialchars($row['category']) ?></u>
                        </div>
                        <p><i><?= nl2br(htmlspecialchars($row['text'])) ?></i></p>
                    </div>
                    <div class="bottom">
                        <span>Опубликовано: <em class="user"><?= htmlspecialchars($row['author_login']) ?></em></span>
                        <u class="time_post"><?= date('d.m.Y H:i', strtotime($row['timestamp'])) ?></u>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-posts">
                    <p>Пока нет ни одного поста</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="style/scripts/sidebar.js"></script>
</body>
</html>