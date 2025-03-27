<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');
$user_id = $_SESSION['user_id']; // Ambil ID user yang sedang login

$query = "SELECT 
            g.id, 
            g.name,  
            COUNT(DISTINCT t.id) AS task_count,
            COUNT(DISTINCT gm2.user_id) AS member_count
          FROM `groups` g
          INNER JOIN group_members gm ON g.id = gm.group_id
          LEFT JOIN group_tasks t ON g.id = t.group_id
          LEFT JOIN group_members gm2 ON g.id = gm2.group_id
          WHERE gm.user_id = :user_id
          GROUP BY g.id, g.name";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App - Groups</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex">

    <!-- Sidebar -->
    <div class="w-64 fixed bg-gray-800 h-screen p-6 flex flex-col">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8">
            📖 To-Do List
        </h2>

        <!-- Sidebar Menu -->
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
            <h2 class="text-xl font-semibold text-gray-200 mb-8 flex items-center gap-2">
                My Groups
            </h2>

            <a href="form_group.php" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">➕ Add Group</a>

            <!-- Daftar Grup -->
            <?php if (empty($groups)): ?>
                <p class="text-center text-gray-500">Kamu belum bergabung dalam grup mana pun.</p>
            <?php else: ?>
                <?php foreach ($groups as $group): ?>
                    <div class="my-4 p-4 bg-gray-700 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <div class="flex gap-2 items-center">
                                <span class="font-semibold"><?php echo htmlspecialchars($group['name']); ?></span>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-sm text-gray-400">📝 Task: <?php echo $group['task_count']; ?> | 👥 Member: <?php echo $group['member_count']; ?></span>
                            <a href="group_detail.php?id=<?php echo $group['id']; ?>" class="bg-gray-500 text-white py-1 px-4 rounded-lg hover:bg-gray-600 transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
