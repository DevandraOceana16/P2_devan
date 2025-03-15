<?php
$host = 'localhost';  // Ganti dengan host database Anda
$dbname = 'todolist';  // Ganti dengan nama database Anda
$username = 'root';  // Ganti dengan username database Anda
$password = '';  // Ganti dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>
