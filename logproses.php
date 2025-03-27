<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    

    // Query untuk mencari username dalam database menggunakan prepared statement
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika username ditemukan
    if ($user) {
        // Verifikasi password dengan password_hash yang disimpan di database
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            

            // Redirect ke dashboard atau halaman lain
            header("Location: todolist/index.php");
            exit();
        } else {
            echo "Username atau password salah!";
        }
    } else {
        echo "Username atau password salah!";
    }
}
?>
