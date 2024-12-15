<?php
session_start();
require_once 'db_connection.php';

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['user_id']) || $_SESSION['is_employee'] != 1) {
    echo "Access denied. Staff members only.";
    exit;
}

// Fetch tasks assigned to the logged-in staff member
$user_id = $_SESSION['user_id'];
$query = "SELECT id, title, description, due_date, status 
          FROM tasks 
          WHERE assigned_to = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
</head>
<body>
    <h1>My Task Assignments</h1>
    <?php if ($result->num_rows > 0) { ?>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending') { ?>
                            <form method="POST" action="update_task_status.php">
                                <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="mark_completed">Mark as Completed</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No tasks assigned.</p>
    <?php } ?>
</body>
</html>
