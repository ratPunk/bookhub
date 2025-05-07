<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 
require_once '../database/db.php';
require_once '../php/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../auth/register_form.html?error=' . urlencode('Ошибка'));
}

$login = htmlspecialchars($_POST['login']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$confirm_password = htmlspecialchars($_POST['confirm_password']);

$login = str_replace(' ', '', $login);
$login = strtolower($login);

if (empty($login) || empty($password) || empty($confirm_password)) {
    header('Location: ../../auth/register_form.html?error=' . urlencode('Все обязательные поля должны быть заполнены'));
    exit();
}

if(strlen($login) < 2 || strlen($login) > 20){
    header('Location: ../../auth/register_form.html?error=' . urlencode('Длина login должна быть от 2 до 20 символов'));
    exit();
}

if (!empty($email)) {
    if (strlen($email) < 5 || strlen($email) > 60) {
        header('Location: ../../auth/register_form.html?error=' . urlencode('Длина email должна быть от 5 до 60 символов'));
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../../auth/register_form.html?error=' . urlencode('Неверный формат email'));
        exit();
    }
}

if (strlen($password) < 8 || strlen($password) > 20) {
    header('Location: ../../auth/register_form.html?error=' . urlencode('Длина пароля должна быть от 8 до 20 символов'));
    exit();
}

if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
    header('Location: ../../auth/register_form.html?error=' . urlencode('Пароль должен содержать хотя бы одну заглавную букву, одну строчную букву, одну цифру и один специальный символ'));
    exit();
}

if($password != $confirm_password){
    header('Location: ../../auth/register_form.html?error=' . urlencode('Пароли не совпадают'));
    exit();
}

// Подготовка запроса
$result = $mysqli->query("SELECT login FROM users WHERE login = '$login'");
// Проверка существования пользователя
if ($result->num_rows > 0) {
    $result->close();
    header('Location: ../../auth/register_form.html?error=' . urlencode('Такой пользователь уже существует'));
    exit();
}
$result->close();


$hashedPassword = password_hash($password, PASSWORD_BCRYPT);


$stmt = $mysqli->prepare("INSERT INTO users (username, login, password, email) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$login, $login, $hashedPassword, $email])) {
    $stmt->close();
    $_SESSION['user']['login'] = $login;

    // Получаем ID пользователя
    $query = "SELECT id FROM users WHERE login = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        Logger::log("Пользователь $login не найден в базе данных при создании поста", "ERROR");
        header('Location: ../../account.php?error=' . urlencode('Ошибка создания поста'));
        exit();
    }

    $_SESSION['user']['id'] = $user['id'];

    Logger::log("Пользователь $login зарегестрировался в систему", "INFO");
    header('Location: ../../account.php');
} else {
    $stmt->close();
    $_SESSION['error'] = 'Регистрация не удалась';
    Logger::log("Пользователь $login не смог зарегестрироваться", "DEBUG");
    header('Location: ../../auth/register_form.html');
} 
$stmt->close();