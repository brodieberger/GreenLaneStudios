<?php
$servername="localhost";
$username="berger_brodie";
$password="KeanUniversity!";
$dbname="berger_marina";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
