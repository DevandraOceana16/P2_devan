<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');

// Ambil ID tugas yang akan dihapus
if (isset($_POST['task_id']) && is_numeric($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
    $user_id = $_SESSION['user_id'];

    // Periksa apakah pengguna adalah admin grup atau pencipta tugas
    $query = "SELECT t.group_id, t.created_by, g.created_by AS group_creator
              FROM group_tasks t
              JOIN `groups` g ON t.group_id = g.id
              WHERE t.id = :task_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        // Hanya admin grup atau pencipta tugas yang bisa menghapus tugas
        if ($task['created_by'] == $user_id || $task['group_creator'] == $user_id) {
            // Hapus tugas dari tabel group_tasks
            $query = "DELETE FROM group_tasks WHERE id = :task_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            // Hapus juga assign task yang terkait dengan tugas
            $query = "DELETE FROM group_task_assignees WHERE task_id = :task_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            // Redirect kembali ke halaman grup setelah menghapus
            header("Location: group_detail.php?id=" . $task['group_id']);
            exit;
        } else {
            echo "<script>alert('Anda tidak memiliki hak untuk menghapus tugas ini!');</script>";
            header("Location: group_detail.php?id=" . $task['group_id']);
            exit;
        }
    } else {
        echo "<script>alert('Tugas tidak ditemukan!');</script>";
        header("Location: group.php");
        exit;
    }
} else {
    echo "<script>alert('ID tugas tidak valid!');</script>";
    header("Location: group.php");
    exit;
}
?>
