<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Debugging: Log the raw POST data
    error_log("POST data: " . print_r($_POST, true));

    if ($action === 'create_incident') {
        // Prepare the incident data
        $date = $_POST['date'];
        $type = $_POST['type'];
        $description = $_POST['description'];

        // Insert the incident into the database
        $stmt = $conn->prepare("INSERT INTO incidents (incident_date, incident_type, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $date, $type, $description);
        if ($stmt->execute()) {
            error_log("Incident added successfully: $type on $date");
        } else {
            error_log("Error in inserting incident: " . $stmt->error);
        }
        $stmt->close();
    } elseif ($action === 'delete_incident') {
        $stmt = $conn->prepare("DELETE FROM incidents WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);
        if ($stmt->execute()) {
            error_log("Incident deleted successfully. ID: " . $_POST['id']);
        } else {
            error_log("Error in deleting incident: " . $stmt->error);
        }
        $stmt->close();
    }
}

// Fetch all incidents
$result = $conn->query("SELECT * FROM incidents ORDER BY incident_date DESC");
$incidents = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $incidents[] = $row;
    }
} else {
    error_log("Error fetching incidents: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marina Incident Management</title>
    <script>
        // Add sorting functionality for the table
        function sortTable(n) {
            const table = document.getElementById("incidentTable");
            let rows, switching, i, x, y, shouldSwitch, dir, switchCount = 0;
            switching = true;
            dir = "asc"; // Set the sorting direction to ascending

            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir === "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchCount++;
                } else {
                    if (switchCount === 0 && dir === "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
            // Update the sorting direction indicator
            updateSortIndicator(n, dir);
        }

        function updateSortIndicator(columnIndex, direction) {
            const headers = document.querySelectorAll("#incidentTable th");
            headers.forEach((header, index) => {
                const span = header.querySelector("span");
                if (span) span.innerHTML = index === columnIndex ? (direction === "asc" ? " ▲" : " ▼") : "";
            });
        }
    </script>
</head>
<body>
    <h1>Marina Incident Management</h1>

    <!-- Add New Incident -->
    <form method="POST" action="incidents.php">
        <h2>Log an Incident</h2>
        <input type="hidden" name="action" value="create_incident">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="type">Incident Type:</label>
        <select id="type" name="type" required>
            <option value="Damage to Boat">Damage to Boat</option>
            <option value="Injury">Injury</option>
            <option value="Property Damage">Property Damage</option>
            <option value="Violation of Rules">Violation of Rules</option>
            <option value="Other">Other</option>
        </select><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" placeholder="Provide details of the incident" required></textarea><br><br>

        <button type="submit">Log Incident</button>
    </form>

    <!-- Incidents Table -->
    <h2>Logged Incidents</h2>
    <table border="1" id="incidentTable">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Date<span></span></th>
                <th onclick="sortTable(1)">Type<span></span></th>
                <th onclick="sortTable(2)">Description<span></span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($incidents as $incident): ?>
                <tr>
                    <td><?= htmlspecialchars($incident['incident_date']) ?></td>
                    <td><?= htmlspecialchars($incident['incident_type']) ?></td>
                    <td><?= htmlspecialchars($incident['description']) ?></td>
                    <td>
                        <!-- Delete Form -->
                        <form method="POST" action="incidents.php" style="display:inline-block;">
                            <input type="hidden" name="action" value="delete_incident">
                            <input type="hidden" name="id" value="<?= $incident['id'] ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this incident?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
