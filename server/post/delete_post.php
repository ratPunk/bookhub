<?php
session_start();
require_once '../database/db.php';
require_once '../php/logger.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user']['id'])) {
    Logger::log("Попытка удаления поста неавторизованным пользователем", "ERROR");
    header("Location: ../../login.php");
    exit();
}

// Проверка наличия ID поста
if (!isset($_GET['id'])) {
    Logger::log("Попытка удаления поста без указания ID", "ERROR");
    header("Location: ../../account.php?error=" . urlencode('Не указан ID поста'));
    exit();
}

$post_id = (int)$_GET['id']; // Приводим к целому числу для безопасности
$user_id = (int)$_SESSION['user']['id'];
$login = $_SESSION['user']['login'];

// Подготовленный запрос для проверки принадлежности поста
$check_sql = "SELECT id FROM posts WHERE id = ? AND author = ?";
$stmt = $mysqli->prepare($check_sql);
$stmt->bind_param('ii', $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Подготовленный запрос для "мягкого" удаления (установка флага deleted)
    $update_sql = "UPDATE posts SET deleted = 1 WHERE id = ?";
    $stmt = $mysqli->prepare($update_sql);
    $stmt->bind_param('i', $post_id);
    
    if ($stmt->execute()) {
        Logger::log("Пользователь $login успешно удалил пост $post_id", "INFO");
        header("Location: ../../account.php?success=" . urlencode('Пост успешно удален'));
    } else {
        Logger::log("Ошибка при удалении поста $post_id пользователем $login: " . $mysqli->error, "ERROR");
        header("Location: ../../account.php?error=" . urlencode('Ошибка при удалении поста'));
    }
    exit();
} else {
    Logger::log("Пользователь $login пытался удалить чужой пост $post_id", "WARNING");
    header("Location: ../../account.php?error=" . urlencode('Нельзя удалить чужой пост'));
    exit();
}