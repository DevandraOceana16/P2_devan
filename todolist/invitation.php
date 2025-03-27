<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');
$user_id = $_SESSION['user_id']; // Ambil ID user yang sedang login

// Ambil daftar undangan grup untuk user yang sedang login
$query = "SELECT gi.id, g.name AS group_name, u.username AS sender_name
          FROM group_invitations gi
          INNER JOIN `groups` g ON gi.group_id = g.id
          INNER JOIN users u ON gi.sender_id = u.id
          WHERE gi.receiver_id = :user_id AND gi.status = 'pending'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$invitations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses jika user menerima atau menolak undangan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['invitation_id'])) {
    $invitation_id = $_POST['invitation_id'];
    $action = $_POST['action'];
    
    if ($action === 'accept') {
        // Ambil informasi undangan
        $query = "SELECT group_id FROM group_invitations WHERE id = :invitation_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':invitation_id', $invitation_id, PDO::PARAM_INT);
        $stmt->execute();
        $invitation = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($invitation) {
            $group_id = $invitation['group_id'];
            
            // Tambahkan user ke dalam grup
            $insertQuery = "INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $insertStmt->execute();
        }
    }
    
    // Hapus undangan setelah diproses
    $deleteQuery = "DELETE FROM group_invitations WHERE id = :invitation_id";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->bindParam(':invitation_id', $invitation_id, PDO::PARAM_INT);
    $deleteStmt->execute();
    
    header("Location: invitation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App - Invitations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex">

    <!-- Sidebar -->
    <div class="w-64 fixed bg-gray-800 h-screen p-6 flex flex-col">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8">📖 To-Do List</h2>
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
            <h2 class="text-xl font-semibold text-gray-200 mb-8">Undangan Grup</h2>
            
            <?php if (empty($invitations)): ?>
                <p class="text-center text-gray-500">Tidak ada undangan grup.</p>
            <?php else: ?>
                <?php foreach ($invitations as $invitation): ?>
                    <div class="mb-4 p-4 bg-gray-700 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Grup: <?php echo htmlspecialchars($invitation['group_name']); ?></span>
                            <span class="text-sm text-gray-400">Diundang oleh: <?php echo htmlspecialchars($invitation['sender_name']); ?></span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="invitation_id" value="<?php echo $invitation['id']; ?>">
                                <button type="submit" name="action" value="accept" class="bg-green-500 text-white py-1 px-4 rounded-lg hover:bg-green-600 transition-colors">Terima</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="invitation_id" value="<?php echo $invitation['id']; ?>">
                                <button type="submit" name="action" value="reject" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-colors">Tolak</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>