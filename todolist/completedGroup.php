<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['task_id'], $_POST['group_id'])) {
    $task_id = $_POST['task_id'];
    $group_id = $_POST['group_id'];
    $user_id = $_SESSION['user_id'];

    // Cek apakah user adalah anggota grup dan memiliki tugas yang sesuai
    $query = "SELECT gta.user_id, gta.completed, t.created_by
              FROM group_task_assignees gta
              INNER JOIN group_tasks t ON gta.task_id = t.id
              WHERE gta.task_id = :task_id AND t.group_id = :group_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':group_id', $group_id);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        // Cek apakah user yang melakukan update adalah admin atau anggota yang ditugaskan
        if ($task['user_id'] == $user_id || $task['created_by'] == $user_id) {
            // Update status tugas ke status yang baru
            $new_status = $task['completed'] ? 0 : 1;
            $query = "UPDATE group_task_assignees SET completed = :completed WHERE task_id = :task_id AND user_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':completed', $new_status);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            echo "Status tugas berhasil diperbarui.";
        } else {
            echo "Anda tidak berhak mengubah status tugas ini.";
        }
    } else {
        echo "Tugas tidak ditemukan.";
    }
} else {
    echo "Data tidak valid.";
}
?>
