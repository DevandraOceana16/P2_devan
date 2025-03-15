<?php
// Menyambungkan ke database
include('../conn.php');

// Ambil ID tugas dari URL
$taskId = $_GET['id'] ?? null;

// Jika ID tidak ditemukan, alihkan ke halaman daftar tugas
if (!$taskId) {
    header('Location: index.php');
    exit;
}

// Query untuk menghapus tugas dari database
$query = "DELETE FROM tasks WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $taskId);
$stmt->execute();

// Redirect ke halaman utama setelah penghapusan
header('Location: index.php');
exit;
?>
