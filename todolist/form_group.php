<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');
$user_id = $_SESSION['user_id']; // ID user yang sedang login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_name = $_POST['group_name'];

    try {
        $pdo->beginTransaction();
        
        // 1. Insert data grup
        $stmt = $pdo->prepare("INSERT INTO `groups` (name, created_by) VALUES (:name,  :created_by)");
        $stmt->execute([
            ':name' => $group_name,
            ':created_by' => $user_id
        ]);
        
        // Ambil ID grup yang baru dibuat
        $group_id = $pdo->lastInsertId();
        
        // 2. Tambahkan user sebagai admin ke tabel group_members
        $stmt = $pdo->prepare("INSERT INTO group_members (group_id, user_id, role, status) VALUES (:group_id, :user_id, 'admin', 'accepted')");
        $stmt->execute([
            ':group_id' => $group_id,
            ':user_id' => $user_id
        ]);
        
        $pdo->commit();
        
        // Redirect ke halaman group.php setelah berhasil
        header("Location: group.php");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Grup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex justify-center items-center h-screen">
    <div class="bg-gray-800 p-8 rounded-xl shadow-lg w-96">
        <h2 class="text-2xl font-semibold text-gray-200 mb-6">Buat Grup Baru</h2>
        <form action="form_group.php" method="POST">
            <label class="block text-gray-300">Nama Grup:</label>
            <input type="text" name="group_name" class="w-full p-2 mt-1 rounded bg-gray-700 border border-gray-600" required>

            <button type="submit" class="bg-green-500 text-white w-full mt-4 p-2 rounded hover:bg-green-600">Buat Grup</button>
        </form>
        <a href="group.php" class="block text-center text-gray-400 mt-4 hover:text-gray-200">Kembali</a>
    </div>
</body>
</html>
