<?php
include "dbconfig.php";
$con = mysqli_connect($host, $username, $password, $dbname) or die("<br>Cannot connect to DB:$dbname on $host\n");

// Handle form submission for inserting a new row
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['occupiedby'])) {
    // Get the name from the form input and sanitize it
    $occupiedby = mysqli_real_escape_string($con, $_POST['occupiedby']);

    // Insert a new row into the database with 'open' as false (0) and the entered name
    $insertQuery = "INSERT INTO test (open, occupiedby) VALUES (0, '$occupiedby')";
    if (mysqli_query($con, $insertQuery)) {
        echo "New row inserted successfully.<br>";
    } else {
        echo "Error: " . mysqli_error($con) . "<br>";
    }
}

// Handle form submission for deleting a row
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    // Get the ID of the row to delete
    $delete_id = mysqli_real_escape_string($con, $_POST['delete_id']);

    // Delete the row with the given ID
    $deleteQuery = "DELETE FROM test WHERE id = $delete_id";
    if (mysqli_query($con, $deleteQuery)) {
        echo "Row with ID $delete_id deleted successfully.<br>";
    } else {
        echo "Error deleting row: " . mysqli_error($con) . "<br>";
    }
}

$query = "SELECT * FROM test;";
$result = mysqli_query($con, $query);

?>

<html>

<body>
    <div>
        <h1>Hawk Island Marina Management System (Employee View)</h1>

        <?php
        echo "$query <br>";

        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Open</th><th>Occupied By</th></tr>";  // Table headers
        while ($row = mysqli_fetch_array($result)) {
            // Fixing the if statement and logic
            if ($row['open'] == 0) {
                $open_status = 'False';
            } else {
                $open_status = 'True';
            }

            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($open_status) . "</td>
                    <td>" . htmlspecialchars($row['occupiedby']) . "</td></tr>";
        }
        echo "</table>";
        ?>
    </div>

    <div>
        <h1>Add new customers to the table</h1>
        <!-- Form to add a new entry to the database -->
        <form method="POST" action="">
            <label for="occupiedby">Enter someone's name to occupy:</label>
            <input type="text" id="occupiedby" name="occupiedby" required>
            <button type="submit">Submit</button>
        </form>
    </div>

    <div>
        <h1>Delete Row</h1>
        <!-- Form to delete an entry from the database -->
        <form method="POST" action="">
            <label for="delete_id">Select ID to delete:</label>
            <select id="delete_id" name="delete_id" required>
                <option value="">--Select an ID--</option>
                <?php
                // Re-run the query to populate the dropdown with IDs
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='" . htmlspecialchars($row['id']) . "'>ID: " . htmlspecialchars($row['id']) . " - Occupied By: " . htmlspecialchars($row['occupiedby']) . "</option>";
                }
                ?>
            </select>
            <button type="submit">Delete</button>
        </form>
    </div>
</body>

</html>
