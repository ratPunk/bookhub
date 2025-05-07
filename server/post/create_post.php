<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../php/logger.php';
require_once '../database/db.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user']['login'])) {
    Logger::log("Попытка создания поста неавторизованным пользователем", "ERROR");
    header('Location: ../../account.php?error=' . urlencode('Требуется авторизация'));
    exit();
}

$login = $_SESSION['user']['login'];

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Logger::log("Ошибка создания поста у пользователя $login: недопустимый метод запроса", "ERROR");
    header('Location: ../../account.php?error=' . urlencode('Недопустимый метод запроса'));
    exit();
}

// Проверка наличия обязательных полей
$requiredFields = ['category', 'title', 'text_post'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        Logger::log("Пользователь $login пытался создать пост без заполнения обязательного поля: $field", "WARNING");
        header('Location: ../../account.php?error=' . urlencode("Не заполнено обязательное поле: $field"));
        exit();
    }
}

// Очистка и валидация данных
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
    Logger::log("Пользователь $login пытался создать пост с недопустимой категорией: $category", "WARNING");
    header('Location: ../../account.php?error=' . urlencode("Выбрана недопустимая категория"));
    exit();
}

// Проверка длины заголовка
if (mb_strlen($title) < 5 || mb_strlen($title) > 100) {
    Logger::log("Пользователь $login пытался создать пост с некорректной длиной заголовка", "WARNING");
    header('Location: ../../account.php?error=' . urlencode("Заголовок должен содержать от 5 до 100 символов"));
    exit();
}

// Проверка длины текста поста
if (mb_strlen($text_post) < 20 || mb_strlen($text_post) > 2000) {
    Logger::log("Пользователь $login пытался создать пост с некорректной длиной текста", "WARNING");
    header('Location: ../../account.php?error=' . urlencode("Текст поста должен содержать от 20 до 2000 символов"));
    exit();
}

try {
    // Получаем ID пользователя
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        Logger::log("Пользователь $login не найден в базе данных при создании поста", "ERROR");
        header('Location: ../../account.php?error=' . urlencode('Ошибка создания поста'));
        exit();
    }
    
    $userId = $user['id'];
    
    // Вставляем пост в базу данных со всеми полями
    $stmt = $mysqli->prepare("INSERT INTO posts (title, text, category, deleted, author, timestamp, changed) 
                             VALUES (?, ?, ?, 0, ?, NOW(), 0)");
    
    $stmt->bind_param('sssi', $title, $text_post, $category, $userId);
    
    if ($stmt->execute()) {
        $postId = $mysqli->insert_id;
        Logger::log("Пользователь $login успешно создал пост с ID $postId", "INFO");
        header('Location: ../../account.php?success=' . urlencode('Пост успешно создан'));
        exit();
    } else {
        throw new Exception($mysqli->error);
    }
    
} catch (Exception $e) {
    Logger::log("Ошибка при создании поста пользователем $login: " . $e->getMessage(), "ERROR");
    header('Location: ../../account.php?error=' . urlencode('Ошибка при сохранении поста'));
    exit();
}