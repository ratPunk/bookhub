<?php
session_start();
require_once '../database/db.php';
require_once '../php/logger.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user'])) {
    Logger::log("Попытка редактирования поста неавторизованным пользователем", "WARNING");
    header("Location: ../../login.php?message=" . urlencode('Требуется авторизация для редактирования постов'));
    exit();
}

// Проверка наличия ID поста
if (!isset($_GET['id'])) {
    Logger::log("Пользователь ".$_SESSION['user']['login']." пытался редактировать пост без указания ID", "WARNING");
    header("Location: ../../account.php?message=" . urlencode('Не указан ID поста для редактирования'));
    exit();
}

$post_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user']['id'];
$login = $_SESSION['user']['login'];

// Проверка существования и принадлежности поста
$check_sql = "SELECT * FROM posts WHERE id = ? AND author = ? AND deleted = 0";
$stmt = $mysqli->prepare($check_sql);
$stmt->bind_param('ii', $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    Logger::log("Пользователь $login пытался редактировать несуществующий или чужой пост ID: $post_id", "WARNING");
    header('Location: ../../account.php?error=' . urlencode('Пост не найден или недоступен для редактирования'));
    exit();
}

$post = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование поста - BookHub</title>
    <link rel="stylesheet" href="../../style/css/header.css">
    <link rel="stylesheet" href="../../style/css/sidebar.css">
    <link rel="stylesheet" href="../../style/css/account.css">
    <style>
        textarea {
            min-height: 200px;
            width: 100%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php require '../../modules/header.php' ?>
    <?php require '../../modules/sidebar.php' ?>

    <main>
        <div class="form-container">
            <h1 class="form-title">Редактирование поста</h1>
            <form action="update_post.php" method="post">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                
                <div class="input-group">
                    <label>Категория:</label>
                    <div class="radio-group">
                        <?php
                        $categories = [
                            'Классическая литература',
                            'Современная литература',
                            'Научная фантастика',
                            'Фэнтези',
                            'Детективы'
                        ];
                        
                        foreach ($categories as $category): ?>
                            <div class="radio-option">
                                <input type="radio" id="<?= strtolower(str_replace(' ', '_', $category)) ?>" 
                                       name="category" value="<?= $category ?>"
                                       <?= $post['category'] == $category ? 'checked' : '' ?>>
                                <label for="<?= strtolower(str_replace(' ', '_', $category)) ?>"><?= $category ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="input-group">
                    <label for="title">Заголовок поста:</label>
                    <input type="text" id="title" name="title" placeholder="Введите заголовок" 
                           value="<?= htmlspecialchars($post['title']) ?>" required>
                </div>

                <div class="input-group">
                    <label for="text_post">Текст поста:</label>
                    <textarea id="text_post" name="text_post" placeholder="Напишите ваш пост здесь..." 
                              maxlength="2000" required><?= htmlspecialchars($post['text']) ?></textarea>
                    <div id="charCounter" class="char-counter"><?= mb_strlen($post['text']) ?>/2000</div>
                </div>

                <input type="submit" value="Сохранить изменения">
            </form>
        </div>
    </main>

    <script>
        // Счетчик символов
        const textarea = document.getElementById('text_post');
        const charCounter = document.getElementById('charCounter');
        const maxLength = 2000;

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