<?php
include 'conn.php';

// Menangani data form yang dikirim dengan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Menghitung hash dari password untuk keamanan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Query untuk memasukkan data ke tabel users
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameter
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        // Eksekusi query
        $stmt->execute();

        // Menampilkan alert jika registrasi berhasil
        echo "<script>
                alert('Registrasi berhasil!');
                window.location.href = 'login.php';
              </script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
