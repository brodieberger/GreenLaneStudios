<?php
include 'db_connection.php'; // Include your database connection file

if (isset($_GET['owner_id'])) {
    $owner_id = intval($_GET['owner_id']);

    $query = "SELECT id, name FROM boats WHERE user_id = $owner_id";
    $result = mysqli_query($conn, $query);

    $boats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $boats[] = $row;
    }

    // Return data as JSON
    header('Content-Type: application/json');
    echo json_encode($boats);
}
