<?php
session_start();
require_once 'logger.php';

$user = $_SESSION['user']['login'];

Logger::log("Пользователь $user вышел из системы", "ERROR");

session_destroy();
header('Location: ../../index.php');
exit();
?>