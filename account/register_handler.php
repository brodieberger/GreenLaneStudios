<?php
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and hash the user inputs
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Check if an account with the given email already exists
    $query = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error preparing query: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($existingEmail);

    $accountExists = false;
    while ($stmt->fetch()) {
        $accountExists = true;
        break;
    }
    $stmt->close();

    if ($accountExists) {
        echo "An account with this email already exists.";
    } else {
        // Insert the new account into the database
        $insert_query = "INSERT INTO users (email, name, password) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        if (!$insert_stmt) {
            die("Error preparing insert query: " . $conn->error);
        }
        $insert_stmt->bind_param("sss", $email, $name, $password);

        if ($insert_stmt->execute()) {
            echo "Account created successfully!";
            header("Refresh: 2; URL=../index.php"); // Redirect to index.php after 2 seconds
            exit;
        } else {
            echo "Error creating account: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
