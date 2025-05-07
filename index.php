<?php
session_start();
require_once 'server/php/logger.php';
require_once 'server/database/db.php';

Logger::log("Пользователь ".$_SESSION['user']['login']." загрузил страницу с постами", "INFO");

// Получаем все посты без фильтрации (фильтрация будет на клиенте)
$sql = "SELECT 
            p.*, 
            (SELECT login FROM users WHERE id = p.author) as author_login 
        FROM posts p 
        WHERE p.deleted = 0
        ORDER BY p.timestamp DESC";

$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
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
    <style>
        .category-btn:hover, 
        .category-btn.active {
            background: #6B46C1;
            color: white;
        }
        .no-posts {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #666;
            display: none;
        }
        .card {
            transition: all 0.3s ease;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <?php require 'modules/header.php' ?>
    <?php require 'modules/sidebar.php' ?>

    <main id="mainContent">
        <div class="category">
            <button class="category-btn active" data-category="all">Все категории</button>
            <button class="category-btn" data-category="Классическая литература">Классическая литература</button>
            <button class="category-btn" data-category="Современная литература">Современная литература</button>
            <button class="category-btn" data-category="Научная фантастика">Научная фантастика</button>
            <button class="category-btn" data-category="Фэнтези">Фэнтези</button>
            <button class="category-btn" data-category="Детективы">Детективы</button>
        </div>

        <div class="container">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="card" data-category="<?= htmlspecialchars($row['category']) ?>">
                    <div class="top">
                        <div class="main-info">
                            <h1 class="title"><?= htmlspecialchars($row['title']) ?></h1>
                            <span class="label"><?= htmlspecialchars($row['category']) ?></span>
                        </div>
                        <p class="post-text"><?= nl2br(htmlspecialchars($row['text'])) ?></p>
                    </div>
                    <div class="bottom">
                        <span>Опубликовано: <em class="user"><?= htmlspecialchars($row['author_login']) ?></em></span>
                        <span class="time_post"><?= date('d.m.Y H:i', strtotime($row['timestamp'])) ?></span>
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
    <script src="style/scripts/sorting.js"></script>
    
</body>
</html>