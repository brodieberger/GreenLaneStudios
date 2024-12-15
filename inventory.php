<?php
require_once 'db_connection.php';

class Inventory {
    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    // Fetch all items
    public function getItems() {
        $result = $this->db->query("SELECT * FROM inventory");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    // Create a new item with duplicate check
    public function createItem($name, $id, $quantity, $price) {
        // Check for duplicate ID
        $check = $this->db->prepare("SELECT COUNT(*) FROM inventory WHERE item_id = ?");
        $check->bind_param("s", $id);
        $check->execute();
        $check->bind_result($count);
        $check->fetch();
        $check->close();

        if ($count > 0) {
            return "Error: Item ID already exists.";
        }

        $stmt = $this->db->prepare("INSERT INTO inventory (item_id, item_name, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $id, $name, $quantity, $price);
        $stmt->execute();
        $stmt->close();
        return "Item added successfully.";
    }

    // Update an existing item
    public function updateItem($id, $name, $quantity, $price) {
        $stmt = $this->db->prepare("UPDATE inventory SET item_name = ?, quantity = ?, price = ? WHERE item_id = ?");
        $stmt->bind_param("sdis", $name, $quantity, $price, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete an item
    public function deleteItem($id) {
        $stmt = $this->db->prepare("DELETE FROM inventory WHERE item_id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$inventory = new Inventory($conn);
$message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $message = $inventory->createItem($_POST['item_name'], $_POST['item_id'], $_POST['quantity'], $_POST['price']);
    } elseif ($action === 'update') {
        $inventory->updateItem($_POST['item_id'], $_POST['item_name'], $_POST['quantity'], $_POST['price']);
        $message = "Item updated successfully.";
    } elseif ($action === 'delete') {
        $inventory->deleteItem($_POST['item_id']);
        $message = "Item deleted successfully.";
    }
}

// Fetch all items for display
$items = $inventory->getItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Inventory System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file -->
</head>
<body>
    <div id="navbar">
        <h1>Inventory Management System</h1>
    </div>

    <?php if ($message): ?>
    <div id="message" style="background-color: #c5fcef; border: 1px solid black; margin: 20px; padding: 10px;">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <div id="content">
        <div id="box">
            <h2>Add or Update Item</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name" placeholder="Item Name" required>
                <label for="item_id">Item ID:</label>
                <input type="text" id="item_id" name="item_id" placeholder="Item ID" required>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" placeholder="Quantity" required>
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" placeholder="Price" required>
                <button type="submit" style="margin-top: 10px;">Add Item</button>
            </form>
        </div>

        <div id="box1">
            <h2>Current Inventory</h2>
            <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #BEA664; color: black;">
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <form method="POST">
                            <td><input type="text" name="item_id" value="<?= htmlspecialchars($item['item_id']) ?>" readonly></td>
                            <td><input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>"></td>
                            <td><input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>"></td>
                            <td><input type="number" step="0.01" name="price" value="<?= htmlspecialchars($item['price']) ?>"></td>
                            <td>
                                <input type="hidden" name="action" value="update">
                                <button type="submit">Update</button>
                                <button type="submit" formaction="?action=delete" onclick="return confirm('Are you sure?');">Delete</button>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="footer">
        <p>&copy; 2024 Interactive Inventory System. All Rights Reserved.</p>
    </div>
</body>
</html>
