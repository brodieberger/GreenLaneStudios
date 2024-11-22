<?php
session_start();
include 'db_connection.php';
$email = ($_SESSION['user']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $spot = intval($_POST['spot']); // Sanitize and ensure spot is an integer

    // Check if the email exists in the users table
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        echo "No user found with this email address.";
        exit;
    }

    // Insert the reservation into the reservations table
    $insert_query = "INSERT INTO reservations (email, spot_requested) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("si", $email, $spot);  // Bind email as string and spot as integer

    if ($insert_stmt->execute()) {
        echo "Spot requested successfully!";
        header("Refresh: 2; URL=reservations.php"); // Redirect to the customer view page
    } else {
        echo "Error: " . $insert_stmt->error;
    }

    $insert_stmt->close();
    $conn->close();
}
?>
