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
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <div class="w-full max-w-5xl bg-gray-800 p-8 rounded-xl shadow-lg">
            <h2 class="text-3xl font-semibold text-gray-200 mb-8 flex items-center gap-2">
                📖 To-Do List App
            </h2>

            <!-- Input Task -->
            <div class="flex gap-4 mb-8">
                <input type="text" id="taskInput" class="flex-1 p-4 border-2 border-gray-600 rounded-lg focus:outline-none focus:border-green-500 text-lg bg-gray-700 placeholder-gray-400" placeholder="Tambahkan tugas baru...">
                
                <!-- Prioritas Dropdown -->
                <select id="priorityInput" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500">
                    <option value="hard">urgent 🔴</option>
                    <option value="medium">Medium 🟡</option>
                    <option value="easy">Easy 🟢</option>
                </select>

                <!-- Due Date and Time Picker -->
                <input type="date" id="dueDateInput" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500">
                <input type="time" id="dueTimeInput" class="p-4 border-2 border-gray-600 rounded-lg bg-gray-700 text-lg focus:outline-none focus:border-green-500">

                <button onclick="addTask()" class="bg-green-500 text-white p-4 rounded-lg text-lg font-semibold hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus"></i> ➕Tambah
                </button>
            </div>

            <!-- Task Category Buttons -->
            <div class="flex gap-4 mb-8">
                <button onclick="filterTasks('all')" class="bg-blue-500 text-white p-4 rounded-lg text-lg font-semibold hover:bg-blue-600 transition-colors">
                📚All Tasks
                </button>
                <button onclick="filterTasks('today')" class="bg-yellow-500 text-black p-4 rounded-lg text-lg font-semibold hover:bg-yellow-600 transition-colors">
                    Today's Tasks
                </button>
                <button onclick="filterTasks('history')" class="bg-red-500 text-white p-4 rounded-lg text-lg font-semibold hover:bg-red-600 transition-colors">
                    📋History Tasks
                </button>
            </div>

            <!-- Task List -->
            <div id="taskList" class="text-gray-300">
                <!-- Task List will appear here -->
            </div>

            <!-- Print Button for History -->
            <div id="printHistoryBtnContainer" class="mt-4 hidden">
                <button onclick="printHistoryTasks()" class="bg-purple-500 text-white p-4 rounded-lg text-lg font-semibold hover:bg-purple-600 transition-colors">
                    🖨️ Print History Tasks
                </button>
            </div>
        </div>
    </div>

    <script>
        // Array to hold tasks
        let tasks = [];

        // Function to add a new task
        function addTask() {
            const taskInput = document.getElementById('taskInput');
            const priorityInput = document.getElementById('priorityInput');
            const dueDateInput = document.getElementById('dueDateInput');
            const dueTimeInput = document.getElementById('dueTimeInput');
            const taskText = taskInput.value.trim();
            const priority = priorityInput.value;
            const dueDate = dueDateInput.value;
            const dueTime = dueTimeInput.value;

            // Validate input
            if (taskText !== '' && dueDate !== '' && dueTime !== '') {
                const dueDateTime = new Date(`${dueDate}T${dueTime}`); // Combine date and time inputs
                const dueDateTimeStr = dueDateTime.toLocaleString(); // Convert to string format

                const newTask = {
                    id: Date.now(),
                    text: taskText,
                    completed: false,
                    priority: priority,
                    dateTime: dueDateTimeStr, // Save custom date and time
                    dueDateTime: dueDateTime // Save as a Date object for sorting
                };

                // Push new task to the tasks array
                tasks.push(newTask);

                // Clear the input fields
                taskInput.value = '';
                dueDateInput.value = '';
                dueTimeInput.value = '';

                // Render all tasks
                renderTasks('all');
            } else {
                alert('Tugas, tanggal, dan waktu harus diisi!');
            }
        }

        // Function to filter tasks based on category
        function filterTasks(category) {
            let filteredTasks = [];

            if (category === 'all') {
                filteredTasks = tasks;
                document.getElementById('printHistoryBtnContainer').classList.add('hidden');
            } else if (category === 'today') {
                const today = new Date().toLocaleDateString(); // Get today's date
                filteredTasks = tasks.filter(task => task.dueDateTime.toLocaleDateString() === today && !task.completed);
                document.getElementById('printHistoryBtnContainer').classList.add('hidden');
            } else if (category === 'history') {
                filteredTasks = tasks.filter(task => task.completed);
                document.getElementById('printHistoryBtnContainer').classList.remove('hidden');
            }

            renderTasks(filteredTasks);
        }

        // Function to toggle task completion status
        function toggleTaskCompletion(id) {
            const task = tasks.find(task => task.id === id);
            if (task) {
                task.completed = !task.completed;
                renderTasks('all'); // Re-render all tasks after toggling
            }
        }

        // Function to delete a task
        function deleteTask(id) {
            tasks = tasks.filter(task => task.id !== id);
            renderTasks('all');
        }

        // Function to edit a task
        function editTask(id) {
            const task = tasks.find(task => task.id === id);
            const newText = prompt('Edit task:', task.text);

            if (newText && newText.trim() !== '') {
                task.text = newText.trim();
                renderTasks('all');
            }
        }

        // Function to render the task list
        function renderTasks(filteredTasks) {
            const taskList = document.getElementById('taskList');
            taskList.innerHTML = ''; // Clear the list before re-rendering

            if (filteredTasks.length === 0) {
                taskList.innerHTML = '<p class="text-center text-gray-500">No tasks found</p>';
                return;
            }

            filteredTasks.forEach((task, index) => {
                const taskStatusClass = task.completed ? 'line-through text-gray-500' : '';
                const priorityClass = task.priority === 'hard' ? 'bg-red-600 text-white' :
                                      task.priority === 'medium' ? 'bg-yellow-500 text-black' :
                                      'bg-green-600 text-white';

                const row = document.createElement('div');
                row.className = 'mb-4 p-4 bg-gray-700 rounded-lg shadow-md';
                row.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div class="flex gap-2 items-center">
                            <span class="font-semibold">${index + 1}.</span>
                            <span class="${taskStatusClass}">${task.text}</span>
                        </div>
                        <div class="flex gap-4">
                            <span class="px-3 py-1 rounded-full ${priorityClass}">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span>
                            <span class="text-sm">${task.dateTime}</span>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-sm text-gray-400">Due Date: ${task.dueDateTime.toLocaleString()}</span>
                        <button onclick="toggleTaskCompletion(${task.id})" class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                            ${task.completed ? 'Completed' : 'Pending'}
                        </button>
                    </div>
                    <div class="flex justify-between mt-2">
                        <button onclick="editTask(${task.id})" class="bg-yellow-500 text-white py-1 px-4 rounded-lg hover:bg-yellow-600 transition-colors">Edit</button>
                        <button onclick="deleteTask(${task.id})" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-colors">Delete</button>
                    </div>
                `;
                taskList.appendChild(row);
            });
        }

        // Function to print the history tasks
        function printHistoryTasks() {
            const taskList = document.getElementById('taskList');
            const taskListClone = taskList.cloneNode(true);

            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Print History Tasks</title><style>body { font-family: Arial, sans-serif; background-color: #2d2d2d; color: #fff; padding: 20px; } h2 { text-align: center; font-size: 24px; } .task { margin-bottom: 20px; padding: 10px; background-color: #333; border-radius: 8px; } .completed { text-decoration: line-through; color: #999; }</style></head><body>');
            printWindow.document.write('<h2>History Tasks</h2>');
            printWindow.document.write('<div>' + taskListClone.innerHTML + '</div>');
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.onload = function () {
                printWindow.print();
            };
        }
    </script>

</body>
</html>
