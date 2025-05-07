<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../database/db.php';
require_once '../php/logger.php';

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../auth/login_form.html?error=' . urlencode('Недопустимый метод запроса'));
    exit();
}

// Валидация входных данных
$login = htmlspecialchars($_POST['login']);
$password = htmlspecialchars($_POST['password']);

$login = str_replace(' ', '', $login);
$login = strtolower($login);

if (empty($login)) {
    header('Location: ../../auth/login_form.html?error=' . urlencode('Введите login или email'));
    exit();
}

if (empty($password)) {
    header('Location: ../../auth/login_form.html?error=' . urlencode('Введите пароль'));
    exit();
}

// Подготовленный запрос с обработкой ошибок
try {
    $stmt = $mysqli->prepare("SELECT id, username, password, email FROM users WHERE username = ? OR email = ? LIMIT 1");
    if (!$stmt) {
        throw new Exception('Ошибка подготовки запроса: ' . $mysqli->error);
    }
    
    $stmt->bind_param('ss', $login, $login);
    if (!$stmt->execute()) {
        throw new Exception('Ошибка выполнения запроса: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user || !password_verify($password, $user['password'])) {
        Logger::log("Неудачная попытка входа пользователя $login", "ERROR");
        header('Location: ../../auth/login_form.html?error=' . urlencode('Неверный логин или пароль'));
        exit();
    }

    // Успешная аутентификация
    $_SESSION['user']['login'] = $login;
    $_SESSION['user']['id'] = $user['id'];

    // Регенерация ID сессии для защиты от фиксации
    session_regenerate_id(true);

    Logger::log("Пользователь $login вошёл в систему", "INFO");

    header('Location: ../../account.php');
    exit();
    
} catch (Exception $e) {
    error_log('Ошибка аутентификации: ' . $e->getMessage());
    header('Location: ../../auth/login_form.html?error=' . urlencode('Произошла ошибка при входе в систему'));
    exit();
}