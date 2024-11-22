<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Here, you would typically send an email with a password reset link.
        echo "A password reset link has been sent to your email.";
    } else {
        echo "If an account with this email exists, a password reset link has been sent.";
    }

    $stmt->close();
    $conn->close();
}
?>
