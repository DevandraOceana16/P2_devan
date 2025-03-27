<?php
session_start();
include('../conn.php');

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Ubah status tugas menjadi belum selesai (0)
    $query = "UPDATE individual_tasks SET completed = 0 WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $taskId);
    $stmt->execute();
}

// Redirect kembali ke halaman utama dengan filter history
header("Location: index.php?filter=history");
exit();
?>
