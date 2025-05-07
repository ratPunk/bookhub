<?php
session_start();
require_once '../database/db.php';
require_once '../php/logger.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user']['id'])) {
    Logger::log("Попытка восстановления поста неавторизованным пользователем", "WARNING");
    header("Location: ../../login.php?message=" . urlencode('Требуется авторизация для восстановления постов'));
    exit();
}

// Проверка наличия ID поста
if (!isset($_GET['id'])) {
    Logger::log("Попытка восстановления поста без указания ID пользователем ".$_SESSION['user']['login'], "WARNING");
    header("Location: ../../account.php?message=" . urlencode('Не указан ID поста для восстановления'));
    exit();
}

$post_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user']['id'];
$login = $_SESSION['user']['login'];

// Проверка существования и принадлежности поста
$check_sql = "SELECT id, title FROM posts WHERE id = ? AND author = ? AND deleted = 1";
$stmt = $mysqli->prepare($check_sql);
$stmt->bind_param('ii', $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $post_data = $result->fetch_assoc();
    $post_title = $post_data['title'];
    
    // Восстановление поста
    $restore_sql = "UPDATE posts SET deleted = 0, changed = 1 WHERE id = ?";
    $stmt = $mysqli->prepare($restore_sql);
    $stmt->bind_param('i', $post_id);
    
    if ($stmt->execute()) {
        // Логирование успешного восстановления
        Logger::log("Пользователь $login восстановил пост #$post_id: '$post_title'", "NOTICE");
        header("Location: ../../account.php?success=" . urlencode("Пост '$post_title' успешно восстановлен!"));
    } else {
        // Логирование ошибки БД
        Logger::log("Ошибка БД при восстановлении поста #$post_id пользователем $login: ".$mysqli->error, "ERROR");
        header("Location: ../../account.php?error=" . urlencode('Ошибка базы данных при восстановлении'));
    }
    exit();
} else {
    // Пост не найден или не принадлежит пользователю
    $additional_check = $mysqli->query("SELECT id FROM posts WHERE id = $post_id AND deleted = 0");
    
    if ($additional_check->num_rows > 0) {
        Logger::log("Пользователь $login пытался восстановить уже активный пост #$post_id", "INFO");
        header("Location: ../../account.php?info=" . urlencode('Этот пост уже активен'));
    } else {
        Logger::log("Попытка восстановления несуществующего/чужого поста #$post_id пользователем $login", "WARNING");
        header("Location: ../../account.php?error=" . urlencode('Пост не найден или вам недоступен'));
    }
    exit();
}