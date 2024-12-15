<?php
session_start();
require_once 'db_connection.php';

// Check if staff is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['is_employee'] != 1) {
    echo "Access denied.";
    exit;
}

// Update the status of the task to 'completed'
if (isset($_POST['mark_completed']) && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $query = "UPDATE tasks SET status = 'completed' WHERE id = ? AND assigned_to = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo "Task marked as completed.";
    } else {
        echo "Failed to update task status.";
    }
}
header("Location: view_tasks.php");
?>
