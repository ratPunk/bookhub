<?php
$username = 'root';
$password = 'root';
$host = 'localhost';
$db = 'bookhub';

$mysqli = new mysqli($host, $username, $password, $db);

if($mysqli->connect_error){
    echo 'error';
}