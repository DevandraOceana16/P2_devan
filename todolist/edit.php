<?php
include('../conn.php');

// Ambil ID tugas dari URL
$taskId = $_GET['id'] ?? null;

// Jika ID tidak ditemukan, alihkan ke halaman daftar tugas
if (!$taskId) {
    header('Location: index.php');
    exit;
}

// Ambil data tugas dari database
$query = "SELECT * FROM tasks WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $taskId);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika tugas tidak ditemukan, alihkan ke halaman daftar tugas
if (!$task) {
    header('Location: index.php');
    exit;
}

// Proses pengeditan tugas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskText = $_POST['task'];
    $priority = $_POST['priority'];
    $dueDate = $_POST['due_date'];
    $dueTime = $_POST['due_time'];
    $dueDateTime = $dueDate . ' ' . $dueTime;

    // Query untuk memperbarui tugas di database
    $query = "UPDATE tasks SET text = :text, priority = :priority, due_date_time = :due_date_time WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':text', $taskText);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':due_date_time', $dueDateTime);
    $stmt->bindParam(':id', $taskId);
    $stmt->execute();

    header('Location: index.php');
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
            <input type="text" name="task" value="<?php echo htmlspecialchars($task['text']); ?>" class="w-full p-4 border-2 border-gray-600 rounded-lg focus:outline-none focus:border-green-500 text-lg bg-gray-700 placeholder-gray-400 mb-4" placeholder="Edit tugas..." required>
            
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
        </form>
    </div>

</body>
</html>
