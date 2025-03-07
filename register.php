<?php
include 'conn.php';

// Menangani data form yang dikirim dengan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Menghitung hash dari password untuk keamanan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Query untuk memasukkan data ke tabel users
    $sql = "INSERT INTO user (username, password, email) VALUES ('$username', '$hashedPassword', '$email')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil! <a href='login.html'>Login</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Menutup koneksi
    $conn->close();
}
?>
