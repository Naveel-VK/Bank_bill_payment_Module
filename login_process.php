<?php
session_start();

// Database connection parameters
$servername = "localhost"; // Change this to your database server name
$dbUsername = "root"; // Change this to your database username
$dbPassword = ""; // Change this to your database password
$dbname = "banks2"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];

// Use prepared statement to prevent SQL injection
$sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
$sql->bind_param("s", $username);
$sql->execute();
$result = $sql->get_result();

if ($result !== false && $result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        
        $_SESSION['username'] = $username;
        header("Location: welcome.php");
        exit();
    } else {
      
        $_SESSION['login_error'] = "Incorrect username or password.";
        header("Location: index.php");
        exit();
    }
} else {
   
    $_SESSION['login_error'] = "Incorrect username or password.";
    header("Location: index.php");
    exit();
}


$sql->close();
$conn->close();
?>
