<?php
session_start();
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if user exists and get their details
    $query = "SELECT email, password, is_employee FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($dbEmail, $dbPassword, $isEmployee);
    $userFound = false;

    // Fetch the single row
    if ($stmt->fetch()) {
        $userFound = true;
        if (password_verify($password, $dbPassword)) {
            $_SESSION['user'] = $dbEmail; // Store user email
            $_SESSION['is_employee'] = $isEmployee; // Store employee status
            header("Location: ../index.php"); // Redirect to main page
            exit();
        } else {
            echo "Invalid password.";
        }
    }

    if (!$userFound) {
        echo "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
