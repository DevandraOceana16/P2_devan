<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');

$group_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Cek apakah user adalah member grup ini
$query = "SELECT g.*, gm.user_id AS is_member, (g.created_by = :user_id) AS is_admin
          FROM `groups` g
          LEFT JOIN group_members gm ON g.id = gm.group_id AND gm.user_id = :user_id
          WHERE g.id = :group_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':group_id', $group_id);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$group = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika grup tidak ditemukan atau user bukan member, redirect
if (!$group || !$group['is_member']) {
    header("Location: group.php");
    exit;
}

// Ambil daftar anggota grup
$query = "SELECT u.id, u.username 
          FROM users u
          INNER JOIN group_members gm ON u.id = gm.user_id
          WHERE gm.group_id = :group_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':group_id', $group_id);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil daftar task dalam grup
$query = "SELECT t.id, t.text, t.priority, t.due_date_time, t.created_by, u.username AS assigned_user, gta.completed
          FROM group_tasks t
          INNER JOIN group_task_assignees gta ON t.id = gta.task_id
          INNER JOIN users u ON gta.user_id = u.id
          WHERE t.group_id = :group_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':group_id', $group_id);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Tambah task (hanya admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text']) && $group['is_admin']) {
    $text = trim($_POST['text']);
    $priority = intval($_POST['priority']);
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];
    $assigned_user_id = $_POST['assigned_user'];

    // Gabungkan tanggal dan waktu ke format DATETIME
    $due_date_time = $due_date . ' ' . $due_time . ':00';

    // Tambahkan task ke grup
    $query = "INSERT INTO group_tasks (group_id, text, priority, due_date_time, created_by) 
              VALUES (:group_id, :text, :priority, :due_date_time, :user_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':group_id', $group_id);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':due_date_time', $due_date_time);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $task_id = $pdo->lastInsertId();

    // Assign task ke user tertentu
    $query = "INSERT INTO group_task_assignees (task_id, user_id) VALUES (:task_id, :user_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':user_id', $assigned_user_id);
    $stmt->execute();

    header("Location: group_detail.php?id=$group_id");
    exit;
}


// Invite user ke grup (hanya admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invite_email']) && $group['is_admin']) {
    $invite_email = $_POST['invite_email'];

    // Cek apakah email ada di database
    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $invite_email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $invite_user_id = $user['id'];

        // Cek apakah user sudah menjadi anggota grup
        $query = "SELECT 1 FROM group_members WHERE group_id = :group_id AND user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':user_id', $invite_user_id);
        $stmt->execute();

        if ($stmt->fetch()) {
            echo "<script>alert('User sudah menjadi anggota grup!');</script>";
        } else {
            // Cek apakah user sudah memiliki undangan pending
            $query = "SELECT 1 FROM group_invitations WHERE group_id = :group_id AND receiver_id = :user_id AND status = 'pending'";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':group_id', $group_id);
            $stmt->bindParam(':user_id', $invite_user_id);
            $stmt->execute();

            if ($stmt->fetch()) {
                echo "<script>alert('User sudah memiliki undangan yang belum diterima!');</script>";
            } else {
                // Masukkan undangan ke tabel group_invitations
                $query = "INSERT INTO group_invitations (group_id, sender_id, receiver_id) VALUES (:group_id, :sender_id, :receiver_id)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':group_id', $group_id);
                $stmt->bindParam(':sender_id', $user_id); // Admin yang mengundang
                $stmt->bindParam(':receiver_id', $invite_user_id);
                $stmt->execute();

                echo "<script>alert('User berhasil diundang!');</script>";
            }
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Grup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex">

    <!-- Sidebar -->
    <div class="w-64 fixed bg-gray-800 h-screen p-6 flex flex-col">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8">
            📖 To-Do List
        </h2>
        <div class="flex flex-col gap-4">
            <a href="index.php" class="text-lg text-gray-200 hover:text-green-500">🏠 Dashboard</a>
            <a href="task.php" class="text-lg text-gray-200 hover:text-green-500">📖 Task</a>
            <a href="group.php" class="text-lg text-gray-200 hover:text-green-500">👥 Groups</a>
            <a href="invitation.php" class="text-lg text-gray-200 hover:text-green-500">👥 Invitation</a>
        </div>
        <div class="flex flex-col gap-4 mt-auto">
            <a href="logout.php" class="text-lg text-gray-200 hover:text-red-500">🚪 Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8">
        <div class="w-full max-w-5xl bg-gray-800 p-8 rounded-xl shadow-lg">
            <div class="grid grid-cols-2 mb-10">
                <div>
                    <h2 class="text-3xl font-semibold text-gray-200 mb-8">
                        <?php echo htmlspecialchars($group['name']); ?>
                    </h2>

                    <h3 class="text-xl font-semibold text-gray-400 mb-4">👥 Member (<?php echo count($members); ?>)</h3>
                    <ul class="mb-6">
                        <?php foreach ($members as $member): ?>
                            <li class="text-gray-300"><?php echo htmlspecialchars($member['username']); ?></li>
                        <?php endforeach; ?>
                    </ul>

                </div>

                <?php if ($group['is_admin']): ?>
                <div>
                    <!-- Form Invite Member -->
                    <h3 class="text-xl font-semibold text-gray-400 mb-4">📨 Undang User</h3>
                    <form method="POST">
                        <input type="email" name="invite_email" class="p-2 border rounded bg-gray-700 text-white" placeholder="Masukkan Email User" required>
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Undang</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            

            <?php if ($group['is_admin']): ?>
                <!-- Form Tambah Task -->
                <h3 class="text-xl font-semibold text-gray-400 mb-4">➕ Tambahkan Task</h3>
                <form method="POST" class="mb-6">
                    <input type="text" name="text" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500" placeholder="Nama Task" required>

                    <!-- Priority -->
                    <select name="priority" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500">
                        <option value="1">Urgent 🔴</option>
                        <option value="2">Medium 🟡</option>
                        <option value="3">Easy 🟢</option>
                    </select>

                    <!-- Due Date and Time Picker -->
                    <input type="date" name="due_date" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500" required>
                    <input type="time" name="due_time" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500" required>

                    <!-- Pilihan User untuk Assign Task -->
                    <select name="assigned_user" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500">
                        <?php foreach ($members as $member): ?>
                            <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['username']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="bg-green-500 text-white p-4 rounded-lg hover:bg-green-600">Tambah Task</button>
                </form>


                
            <?php endif; ?>

            <h3 class="text-xl font-semibold text-gray-400 mb-4">📝 Task List</h3>
                <ul class="mb- grid gap-2">
                    <?php foreach ($tasks as $task): ?>
                        <?php
                            // Mapping Priority ke Emoji
                            $priority_text = '';
                            switch ($task['priority']) {
                                case 1:
                                    $priority_text = 'Urgent 🔴';
                                    break;
                                case 2:
                                    $priority_text = 'Medium 🟡';
                                    break;
                                case 3:
                                    $priority_text = 'Easy 🟢';
                                    break;
                            }
                        ?>
                        <li class="text-gray-300 bg-gray-700 p-2 rounded-md flex items-center justify-between">
                            <div>
                                <div>
                                    <span class="font-medium"><?php echo htmlspecialchars($task['text']); ?>  -</span>
                                    <span><?php echo htmlspecialchars($task['assigned_user']); ?></span>
                                </div>
                                <div><?php echo $priority_text; ?>, untuk: <?php echo date('d M Y, H:i', strtotime($task['due_date_time'])); ?></div>
                            </div>
                            <div class="flex gap-2">
                            <div class="task-status">
                                <?php 
                                // Menampilkan status penyelesaian tugas
                                if ($task['completed']) {
                                    echo "<span class='text-green-500'>Tuntas ✔️</span>";
                                } else {
                                    echo "<span class='text-red-500'>Belum Tuntas ❌</span>";
                                };
                                ?>
                            </div>



                                <!-- Member action: Mark as completed -->
                                <?php if (($group['is_admin'] && $task['assigned_user'] == $_SESSION['username']) || (!$group['is_admin'] && $task['assigned_user'] == $_SESSION['username'])): ?>
                                    <button 
                                        class="toggle-task bg-<?php echo $task['completed'] ? 'gray' : 'green'; ?>-500 text-white p-2 rounded-lg hover:bg-<?php echo $task['completed'] ? 'red' : 'green'; ?>-600"
                                        data-task-id="<?php echo $task['id']; ?>"
                                        data-group-id="<?php echo $group_id; ?>"
                                        data-completed="<?php echo $task['completed'] ? '1' : '0'; ?>"
                                    >
                                        <?php echo $task['completed'] ? 'Batal' : 'Selesai'; ?>
                                    </button>


                                <?php endif; ?>

                                <!-- Admin actions: Edit and Delete -->
                                <?php if ($group['is_admin'] && $task['created_by'] == $user_id): ?>
                                    <form action="edit_taskGroup.php" method="GET">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Edit</button>
                                    </form>

                                    <form action="delete_taskGroup.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Hapus</button>
                                    </form>


                                <?php endif; ?>

                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>


        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".toggle-task").forEach(button => {
            button.addEventListener("click", function() {
                const taskId = this.dataset.taskId;
                const groupId = this.dataset.groupId;
                const isCompleted = this.dataset.completed === "1";

                // Mengirimkan data untuk memperbarui status tugas
                fetch("completedGroup.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `task_id=${taskId}&group_id=${groupId}`
                })
                .then(response => response.text())
                .then(() => {
                    // Mengubah status dan tampilkan perubahannya di UI
                    const taskStatusElement = this.closest('li').querySelector('.task-status'); // Element status di tugas
                    if (isCompleted) {
                        // Ubah UI status menjadi "Belum Tuntas"
                        taskStatusElement.textContent = "Belum Tuntas ❌";
                        taskStatusElement.classList.remove('text-green-500');
                        taskStatusElement.classList.add('text-red-500');

                        this.textContent = "Selesai";
                        this.classList.remove("bg-gray-500", "hover:bg-gray-600");
                        this.classList.add("bg-green-500", "hover:bg-green-600");
                    } else {
                        // Ubah UI status menjadi "Tuntas"
                        taskStatusElement.textContent = "Tuntas ✔️";
                        taskStatusElement.classList.remove('text-red-500');
                        taskStatusElement.classList.add('text-green-500');

                        this.textContent = "Batal";
                        this.classList.remove("bg-green-500", "hover:bg-green-600");
                        this.classList.add("bg-gray-500", "hover:bg-gray-600");
                    }

                    // Update dataset completed untuk next toggle
                    this.dataset.completed = isCompleted ? "0" : "1";
                })
                .catch(error => {
                    console.error("Error updating task status:", error);
                });
            });
        });
    });



    </script>
</body>
</html>
