<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "banks2"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];

  
    $sql = "SELECT * FROM members WHERE member_id='$member_id'";
    
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
      
        header("Location: member_details1.php?member_id=" . $member_id);
        exit();
    } else {
    
        $error_message = "Member not found.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color:black;
            
            background-size: cover;
            background-repeat: no-repeat;
            color:#fff;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: 333533;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .details {
            margin-top: 20px;
        }
        .button {
  position: relative;
  width: 120px;
  height: 40px;
  background-color: #000;
  display: flex;
  align-items: center;
  color: white;
  flex-direction: column;
  justify-content: center;
  border: none;
  padding: 12px;
  gap: 12px;
  border-radius: 8px;
  cursor: pointer;
}

.button::before {
  content: '';
  position: absolute;
  inset: 0;
  left: -4px;
  top: -1px;
  margin: auto;
  width: 128px;
  height: 48px;
  border-radius: 10px;
  background: linear-gradient(-45deg, #e81cff 0%, #40c9ff 100% );
  z-index: -10;
  pointer-events: none;
  transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.button::after {
  content: "";
  z-index: -1;
  position: absolute;
  inset: 0;
  background: linear-gradient(-45deg, #fc00ff 0%, #00dbde 100% );
  transform: translate3d(0, 0, 0) scale(0.95);
  filter: blur(20px);
}

.button:hover::after {
  filter: blur(30px);
}

.button:hover::before {
  transform: rotate(-180deg);
}

.button:active::before {
  scale: 0.7;
}
    </style>
</head>
<body>
    <div class="container">
        
        <h2>WELCOME</h2>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" name="member_id" placeholder="Enter Member ID" required>
            </div>
            <button class="button">
 Search
</button>
        </form>
        
    </div>
</body>
</html>