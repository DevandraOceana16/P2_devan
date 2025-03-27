<?php
session_start();
include('../conn.php');

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    $query = "UPDATE individual_tasks SET completed = 1 WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $taskId);
    $stmt->execute();
}

header("Location: index.php");
exit();
?>
