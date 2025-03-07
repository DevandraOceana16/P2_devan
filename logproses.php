<?php
// Mulai session untuk menyimpan data pengguna setelah login
session_start();
include 'conn.php';
// Menangani data form yang dikirim dengan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query untuk mencari username dalam database
    $sql = "SELECT * FROM user WHERE username='$username'";
    $result = $conn->query($sql);
    
    // Jika username ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password dengan password_hash yang disimpan di database
        if (password_verify($password, $row['password'])) {
            // Login berhasil, simpan username ke session
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];

            // Arahkan pengguna ke halaman dashboard atau halaman lain
            header("Location: index.php");
            exit();
        } else {
            // Password salah
            echo "Username atau password salah!";
        }
    } else {
        // Username tidak ditemukan
        echo "Username atau password salah!";
    }
    
    // Menutup koneksi
    $conn->close();
}
?>
