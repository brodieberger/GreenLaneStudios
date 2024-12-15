<?php
// Start session and include database connection
session_start();
require_once 'db_connection.php';

// Check if the user is logged in and is a staff member
if (!isset($_SESSION['user_id']) || $_SESSION['is_employee'] != 1) {
    echo "Access denied. Staff members only.";
    exit;
}

// Handle form submissions for adding, updating, and deleting customers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password, is_employee) VALUES (?, ?, ?, 0)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            echo "Customer added successfully.";
        } else {
            echo "Error adding customer.";
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $name, $email, $id);
        if ($stmt->execute()) {
            echo "Customer updated successfully.";
        } else {
            echo "Error updating customer.";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Customer deleted successfully.";
        } else {
            echo "Error deleting customer.";
        }
    }
}

// Fetch all customers from the database
$query = "SELECT id, name, email, created_at FROM users WHERE is_employee = 0";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
</head>
<body>
    <h1>Customer Management</h1>

    <!-- Add Customer Form -->
    <h2>Add Customer</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Customer Name" required>
        <input type="email" name="email" placeholder="Customer Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="add">Add Customer</button>
    </form>

    <!-- Display Customers -->
    <h2>Customer Records</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td>
                    <!-- Update Form -->
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                        <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
                        <button type="submit" name="update">Update</button>
                    </form>
                    <!-- Delete Form -->
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php $conn->close(); ?>
</body>
</html>
