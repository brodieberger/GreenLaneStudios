<?php
session_start();
include '../db_connection.php';

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['user'];
    $old_password = $_POST['old_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Check current password
    $query = "SELECT password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($old_password, $user['password'])) {
            $update_query = "UPDATE users SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $new_password, $email);

            if ($update_stmt->execute()) {
                echo "Password changed successfully!";
                header("Refresh: 2; URL=index.php");
            } else {
                echo "Error updating password.";
            }
            $update_stmt->close();
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
