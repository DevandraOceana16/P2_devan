<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - To-Do List App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex">

    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 h-screen p-6 flex flex-col fixed">
        <h2 class="text-3xl font-semibold text-gray-200 mb-8">
            📖 To-Do List
        </h2>

        <!-- Sidebar Menu -->
        <div class="flex flex-col gap-4">
            <a href="index.html" class="text-lg text-gray-200 hover:text-green-500">🏠Dashboard</a>
            <a href="berita.html" class="text-lg text-gray-200 hover:text-green-500">🌐Berita</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 ml-[260px]">
        <h1 class="text-3xl font-semibold text-gray-200 mb-6">📰 Berita Terbaru</h1>
        
        <!-- News Articles -->
        <div class="space-y-6">
            <!-- Article 1 -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-200 mb-4">Judul Berita 1</h2>
                <p class="text-gray-400">Tanggal: 21 Februari 2025</p>
                
                <!-- Image -->
                <img src="https://via.placeholder.com/600x400" alt="Gambar Berita 1" class="w-full h-64 object-cover rounded-lg my-4">
                
                <p class="text-gray-300 mt-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisi vitae suscipit faucibus, lorem libero aliquet dui, eu tincidunt eros orci nec nisi. Integer cursus felis et augue aliquet, et iaculis turpis laoreet.</p>
                <a href="#" class="text-green-500 hover:text-green-600 mt-4 inline-block">Selengkapnya...</a>
            </div>

            <!-- Article 2 -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-200 mb-4">Judul Berita 2</h2>
                <p class="text-gray-400">Tanggal: 20 Februari 2025</p>
                
                <!-- Image -->
                <img src="https://via.placeholder.com/600x400" alt="Gambar Berita 2" class="w-full h-64 object-cover rounded-lg my-4">
                
                <p class="text-gray-300 mt-4">Praesent ac quam eget felis vehicula dictum. Fusce dapibus sapien in dui feugiat, eu pharetra lorem auctor. In hac habitasse platea dictumst. Nulla eget orci at nunc vehicula faucibus et et leo. Aliquam erat volutpat.</p>
                <a href="#" class="text-green-500 hover:text-green-600 mt-4 inline-block">Selengkapnya...</a>
            </div>

            <!-- Article 3 -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-200 mb-4">Judul Berita 3</h2>
                <p class="text-gray-400">Tanggal: 19 Februari 2025</p>
                
                <!-- Image -->
                <img src="https://via.placeholder.com/600x400" alt="Gambar Berita 3" class="w-full h-64 object-cover rounded-lg my-4">
                
                <p class="text-gray-300 mt-4">Phasellus non magna vestibulum, faucibus elit a, volutpat lectus. Ut a lacus ac ante fringilla aliquam at ac mauris. Cras fermentum lorem sed eros ultricies scelerisque. Suspendisse potenti. Aliquam non orci vitae ligula laoreet.</p>
                <a href="#" class="text-green-500 hover:text-green-600 mt-4 inline-block">Selengkapnya...</a>
            </div>
        </div>
    </div>

</body>
</html>
