<?php
// edit_task.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');

$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;
$user_id = $_SESSION['user_id'];

// Fetch task details
$query = "SELECT * FROM group_tasks WHERE id = :task_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':task_id', $task_id);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// If task not found or user is not admin, redirect
if (!$task || $task['created_by'] != $user_id) {
    header("Location: group_detail.php?id=" . $_GET['group_id']);
    exit;
}

// Handle form submission for editing the task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = trim($_POST['text']);
    $priority = intval($_POST['priority']);
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];
    $due_date_time = $due_date . ' ' . $due_time . ':00';

    $query = "UPDATE group_tasks SET text = :text, priority = :priority, due_date_time = :due_date_time WHERE id = :task_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':due_date_time', $due_date_time);
    $stmt->execute();

    header("Location: group_detail.php?id=" . $_GET['group_id']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex flex-col items-center justify-center min-h-screen">

    <div class="w-full max-w-lg bg-gray-800 p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8 text-center">
            📝 Edit Tugas
        </h2>

        <!-- Formulir Edit -->
        <form method="POST">
            <input type="text" name="text" value="<?php echo htmlspecialchars($task['text']); ?>" class="w-full p-4 border-2 border-gray-600 rounded-lg focus:outline-none focus:border-green-500 text-lg bg-gray-700 placeholder-gray-400 mb-4" placeholder="Edit tugas..." required>
            
            <select name="priority" class="w-full p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500 mb-4">
                <option value="1" <?php echo $task['priority'] == 1 ? 'selected' : ''; ?>>Urgent 🔴</option>
                <option value="2" <?php echo $task['priority'] == 2 ? 'selected' : ''; ?>>Medium 🟡</option>
                <option value="3" <?php echo $task['priority'] == 3 ? 'selected' : ''; ?>>Easy 🟢</option>
            </select>

            <input type="date" name="due_date" value="<?php echo substr($task['due_date_time'], 0, 10); ?>" class="w-full p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500 mb-4">
            <input type="time" name="due_time" value="<?php echo substr($task['due_date_time'], 11, 5); ?>" class="w-full p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500 mb-4">

            <button type="submit" class="w-full bg-green-500 text-white p-4 rounded-lg text-lg font-semibold hover:bg-green-600 transition-colors">
                Simpan Perubahan
            </button>

            <button type="button" onclick="window.location.href='group_detail.php'" class="w-full bg-gray-600 text-white p-4 rounded-lg text-lg font-semibold hover:bg-gray-700 transition-colors mt-2">
                Kembali
            </button>

        </form>
    </div>

</body>
</html>
