<?php
include('conn.php');

// Function to add a new task
function addTask($text, $priority, $due_date_time) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tasks (text, priority, due_date_time) VALUES (?, ?, ?)");
    $stmt->execute([$text, $priority, $due_date_time]);
}

// Function to get all tasks
function getTasks() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY due_date_time ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to update task completion status
function updateTaskStatus($id, $completed) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
    $stmt->execute([$completed, $id]);
}

// Function to delete task
function deleteTask($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
}

// Function to get tasks based on a filter
function getFilteredTasks($filter) {
    global $pdo;
    if ($filter == 'history') {
        $stmt = $pdo->query("SELECT * FROM tasks WHERE completed = 1");
    } else if ($filter == 'today') {
        $today = date('Y-m-d');
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE DATE(due_date_time) = ? AND completed = 0");
        $stmt->execute([$today]);
    } else {
        $stmt = $pdo->query("SELECT * FROM tasks ORDER BY due_date_time ASC");
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
