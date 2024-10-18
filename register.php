<?php
session_start();

// Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "banks2";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check for duplicate username
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Error: Username already exists.";
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();

// SQL to insert data into table
$stmt = $conn->prepare("INSERT INTO users (name, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $username, $password);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    header("Location: login.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
