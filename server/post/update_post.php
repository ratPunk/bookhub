<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../server/database/db.php';
require_once '../php/logger.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user'])) {
    Logger::log("Попытка обновления поста неавторизованным пользователем", "WARNING");
    header("Location: ../../login.php?error=" . urlencode('Требуется авторизация для редактирования постов'));
    exit();
}

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Logger::log("Недопустимый метод запроса при попытке обновления поста", "WARNING");
    header("Location: ../../account.php?error=" . urlencode('Недопустимый метод запроса'));
    exit();
}

// Проверка наличия обязательных полей
$requiredFields = ['post_id', 'category', 'title', 'text_post'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        Logger::log("Пользователь ".$_SESSION['user']['login']." пытался обновить пост без заполнения поля: $field", "WARNING");
        header("Location: ../../account.php?error=" . urlencode("Не заполнено обязательное поле: $field"));
        exit();
    }
}

$post_id = (int)$_POST['post_id'];
$user_id = (int)$_SESSION['user']['id'];
$login = $_SESSION['user']['login'];
$category = trim($_POST['category']);
$title = trim($_POST['title']);
$text_post = trim($_POST['text_post']);

// Список разрешенных категорий
$allowedCategories = [
    'Классическая литература',
    'Современная литература',
    'Научная фантастика',
    'Фэнтези',
    'Детективы'
];

// Проверка категории
if (!in_array($category, $allowedCategories)) {
    Logger::log("Пользователь $login пытался обновить пост с недопустимой категорией: $category", "WARNING");
    header("Location: ../../account.php?error=" . urlencode("Выбрана недопустимая категория"));
    exit();
}

// Проверка длины заголовка
if (mb_strlen($title) < 5 || mb_strlen($title) > 100) {
    Logger::log("Пользователь $login пытался обновить пост с некорректной длиной заголовка", "WARNING");
    header("Location: ../../account.php?error=" . urlencode("Заголовок должен содержать от 5 до 100 символов"));
    exit();
}

// Проверка длины текста поста
if (mb_strlen($text_post) < 20 || mb_strlen($text_post) > 2000) {
    Logger::log("Пользователь $login пытался обновить пост с некорректной длиной текста", "WARNING");
    header("Location: ../../account.php?error=" . urlencode("Текст поста должен содержать от 20 до 2000 символов"));
    exit();
}

try {
    // Проверка принадлежности поста
    $check_sql = "SELECT id FROM posts WHERE id = ? AND author = ? AND deleted = 0";
    $stmt = $mysqli->prepare($check_sql);
    $stmt->bind_param('ii', $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        Logger::log("Пользователь $login пытался обновить чужой/несуществующий пост ID: $post_id", "WARNING");
        header("Location: ../../account.php?error=" . urlencode('Пост не найден или недоступен для редактирования'));
        exit();
    }

    // Обновление поста
    $update_sql = "UPDATE posts SET 
                  title = ?, 
                  text = ?, 
                  category = ?, 
                  changed = 1,
                  timestamp = CURRENT_TIMESTAMP 
                  WHERE id = ?";
    
    $stmt = $mysqli->prepare($update_sql);
    $stmt->bind_param('sssi', $title, $text_post, $category, $post_id);
    
    if ($stmt->execute()) {
        Logger::log("Пользователь $login успешно обновил пост ID: $post_id", "INFO");
        header("Location: ../../account.php?success=" . urlencode('Пост успешно обновлен'));
    } else {
        throw new Exception($mysqli->error);
    }
} catch (Exception $e) {
    Logger::log("Ошибка при обновлении поста ID: $post_id пользователем $login: " . $e->getMessage(), "ERROR");
    header("Location: ../../account.php?error=" . urlencode('Ошибка при обновлении поста'));
}

exit();