<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - To-Do List App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex justify-center items-center h-screen">

    <!-- Main Content (Login Form) -->
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8 flex items-center gap-2">
            📝 Login
        </h2>

        <!-- Login Form -->
        <form action="logproses.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-lg text-gray-400">🦸🏾‍♂️ Username</label>
                <input type="text" id="username" name="username" class="w-full p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg text-white placeholder-gray-400 focus:outline-none focus:border-green-500" placeholder="Masukkan username" required>
            </div>

            <div>
                <label for="password" class="block text-lg text-gray-400">🔑 Password</label>
                <input type="password" id="password" name="password" class="w-full p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg text-white placeholder-gray-400 focus:outline-none focus:border-green-500" placeholder="Masukkan password" required>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="w-full bg-green-500 text-white p-4 rounded-lg text-lg font-semibold hover:bg-green-600 transition-colors">
                    Login
                </button>
            </div>

            <div class="text-center text-gray-500 mt-4">
                Belum punya akun? <a href="regist.php" class="text-blue-500 hover:underline">Daftar</a>
            </div>
        </form>
    </div>

</body>
</html>
