<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');

// Ambil data tugas dari database
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter == 'all') {
    $query = "SELECT * FROM tasks WHERE completed = 0 ORDER BY due_date_time ASC";
} elseif ($filter == 'today') {
    $query = "SELECT * FROM tasks WHERE completed = 0 AND DATE(due_date_time) = CURDATE() ORDER BY due_date_time ASC";
} elseif ($filter == 'history') {
    $query = "SELECT * FROM tasks WHERE completed = 1 ORDER BY due_date_time DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Menambahkan tugas baru jika ada form yang disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $taskText = $_POST['task'];
    $priority = $_POST['priority'];
    $dueDate = $_POST['due_date'];
    $dueTime = $_POST['due_time'];

    // Gabungkan tanggal dan waktu untuk menjadi format DATETIME
    $dueDateTime = $dueDate . ' ' . $dueTime;

    // Query untuk menambahkan tugas baru ke database
    $query = "INSERT INTO tasks (text, priority, due_date_time) VALUES (:text, :priority, :due_date_time)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':text', $taskText);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':due_date_time', $dueDateTime);
    $stmt->execute();

    // Redirect untuk menghindari resubmission form
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex">

    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 h-screen p-6 flex flex-col">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8">
            📖 To-Do List
        </h2>

        <!-- New Sidebar Menu -->
        <div class="flex flex-col gap-4">
            <a href="dashboard" class="text-lg text-gray-200 hover:text-green-500">🏠Dashboard</a>
            <a href="berita" class="text-lg text-gray-200 hover:text-green-500">🌐Berita</a>
        </div>
        <div class="flex flex-col gap-4 mt-auto">
    <a href="logout.php" class="text-lg text-gray-200 hover:text-red-500">🚪 Logout</a>
</div>

    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <div class="w-full max-w-5xl bg-gray-800 p-8 rounded-xl shadow-lg">
            <h2 class="text-3xl font-semibold text-gray-200 mb-8 flex items-center gap-2">
                📖 To-Do List App
            </h2>

            <!-- Input Task -->
            <div class="flex gap-4 mb-8">
                <form method="POST">
                    <input type="text" name="task" class="flex-1 p-4 border-2 border-gray-600 rounded-lg focus:outline-none focus:border-green-500 text-lg bg-gray-700 placeholder-gray-400" placeholder="Tambahkan tugas baru..." required>
                    
                    <!-- Prioritas Dropdown -->
                    <select name="priority" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500">
                        <option value="1">Urgent 🔴</option>
                        <option value="2">Medium 🟡</option>
                        <option value="3">Easy 🟢</option>
                    </select>

                    <!-- Due Date and Time Picker -->
                    <input type="date" name="due_date" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500" required>
                    <input type="time" name="due_time" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500" required>

                    <button type="submit" class="bg-green-500 text-white p-4 rounded-lg text-lg font-semibold hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus"></i> ➕Tambah
                    </button>
                </form>
            </div>

            <div class="mb-4">
                <a href="?filter=all" class="bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition-colors">All Task</a>
                <a href="?filter=today" class="bg-green-500 text-white p-3 rounded-lg hover:bg-green-600 transition-colors">Task Today</a>
                <a href="?filter=history" class="bg-gray-500 text-white p-3 rounded-lg hover:bg-gray-600 transition-colors">History</a>
            </div>


            <!-- Task List -->
            <div id="taskList" class="text-gray-300">
                <?php if (empty($tasks)): ?>
                    <p class="text-center text-gray-500">No tasks found</p>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                        <div class="mb-4 p-4 bg-gray-700 rounded-lg shadow-md">
                            <div class="flex justify-between items-center">
                                <div class="flex gap-2 items-center">
                                    <span class="font-semibold"><?php echo htmlspecialchars($task['text']); ?></span>
                                </div>
                                <div class="flex gap-4">
                                    <span class="px-3 py-1 rounded-full 
                                        <?php 
                                        if ($task['priority'] == 1) {
                                            echo 'bg-red-600 text-white';
                                        } elseif ($task['priority'] == 2) {
                                            echo 'bg-yellow-500 text-black';
                                        } else {
                                            echo 'bg-green-600 text-white';
                                        }
                                        ?>">
                                        <?php 
                                        if ($task['priority'] == 1) {
                                            echo 'Urgent 🔴';
                                        } elseif ($task['priority'] == 2) {
                                            echo 'Medium 🟡';
                                        } else {
                                            echo 'Easy 🟢';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-sm text-gray-400">Due: <?php echo $task['due_date_time']; ?></span>

                                <?php if ($task['completed'] == 0): ?>
                                    <!-- Tombol "Selesai" jika tugas belum selesai -->
                                    <a href="complete.php?id=<?php echo $task['id']; ?>" 
                                    class="bg-green-500 text-white py-1 px-4 rounded-lg hover:bg-green-600 transition-colors">
                                        ✅ Selesai
                                    </a>
                                    <a href="edit.php?id=<?php echo $task['id']; ?>" class="bg-yellow-500 text-white py-1 px-4 rounded-lg hover:bg-yellow-600 transition-colors">Edit</a>

                                <?php else: ?>
                                    <!-- Tombol "Batal" jika tugas sudah selesai -->
                                    <a href="undo.php?id=<?php echo $task['id']; ?>" 
                                    class="bg-gray-500 text-white py-1 px-4 rounded-lg hover:bg-gray-600 transition-colors">
                                        ⏪ Batal
                                    </a>
                                <?php endif; ?>

                                <a href="delete.php?id=<?php echo $task['id']; ?>" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-colors">Delete</a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
