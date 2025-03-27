<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('../conn.php');
$user_id = $_SESSION['user_id']; // Ambil ID user yang sedang login

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
    <div class="w-64 fixed bg-gray-800 h-screen p-6 flex flex-col">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8">
            📖 To-Do List
        </h2>

        <!-- New Sidebar Menu -->
        <div class="flex flex-col gap-4">
            <a href="index.php" class="text-lg text-gray-200 hover:text-green-500">🏠Dashboard</a>
            <a href="sosial.php" class="text-lg text-gray-200 hover:text-green-500">🌐Sosial</a>
        </div>
        <div class="flex flex-col gap-4 mt-auto">
            <a href="logout.php" class="text-lg text-gray-200 hover:text-red-500">🚪 Logout</a>
        </div>

    </div>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8">
        <div class="w-full max-w-5xl bg-gray-800 p-8 rounded-xl h-[2000px] shadow-lg">
            
        </div>
    </div>

</body>
</html>
