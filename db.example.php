<?php

$host = 'localhost';
$user = 'forum_user';
$password = 'PASSWORD_HERE';
$database = 'simple_forum';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');