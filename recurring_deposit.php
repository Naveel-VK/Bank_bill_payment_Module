<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banks2";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (isset($_GET['member_id'])) {
    $member_id = sanitize_input($_GET['member_id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
        $paid_installments = sanitize_input($_POST['paid_installments']);
        $remaining_installments = sanitize_input($_POST['remaining_installments']);

        
        $type_of_account = "recurring deposit";

   
        $paid_installments++;

        $sql_update_installment = "UPDATE installments SET paid_installments = $paid_installments, remaining_installments = $remaining_installments WHERE member_id = $member_id";
        if ($conn->query($sql_update_installment) === TRUE) {
            echo "Recurring deposit payment successful";

          
            if ($remaining_installments == 0) {
                echo "Congratulations! You have successfully completed all payments.";
                exit;
            }

       
            $sql_select_installment = "SELECT * FROM installments WHERE member_id = $member_id";
            $result_installment = $conn->query($sql_select_installment);

            if ($result_installment->num_rows > 0) {
                $row_installment = $result_installment->fetch_assoc();
                $inst_amount = $row_installment['inst_amount'];

             
                $amount_to_be_paid = $inst_amount * $remaining_installments;

               
                $sql_insert_transaction = "INSERT INTO transactions (member_id, amount, date, time, done_by, type_of_account) VALUES ('$member_id', '$inst_amount', CURDATE(), CURTIME(), 'Admin', '$type_of_account')";
                if ($conn->query($sql_insert_transaction) === FALSE) {
                    echo "Error inserting transaction details: " . $conn->error;
                }
            } else {
                echo "No installment details found for this member.";
            }
        } else {
            echo "Error updating installment details: " . $conn->error;
        }
    }

    
    $sql_select_installment = "SELECT * FROM installments WHERE member_id = $member_id";
    $result_installment = $conn->query($sql_select_installment);

    if ($result_installment->num_rows > 0) {
        $row_installment = $result_installment->fetch_assoc();
        $total_installments = $row_installment['total_installments'];
        $paid_installments = $row_installment['paid_installments'];
        $inst_amount = $row_installment['inst_amount'];

       
        $remaining_installments = $total_installments - $paid_installments;

        $amount_to_be_paid = $inst_amount * $remaining_installments;

       
        $conn->close();
    } else {
        echo "No installment details found for this member.";
        $conn->close();
        exit;
    }
} else {
    echo "No member ID provided";
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recurring Deposit Details</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: white;
            background-size: cover;
            background-repeat: no-repeat;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px blue;
            position: relative;
            overflow: hidden;
        }
        h2 {
            text-align: center;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        input[type="submit"]:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .back-button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .back-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .back-button img {
            width: 26px;
            height: 26px;
        }
        .home-button {
            position: absolute;
            bottom: 540px;
            left: calc( 900px);
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php if(isset($inst_amount)) { ?>
        <div class="container">
            <?php 
                if ($remaining_installments == 0) {
                    echo "<h2>Congratulations! You have successfully completed all payments.</h2>";
                }
            ?>
            <h2>Recurring Deposit Details</h2>
            <form action="" method="post">
                <label for="inst_amount">Installment Amount:</label>
                <input type="text" id="inst_amount" name="inst_amount" value="<?php echo $inst_amount; ?>" readonly><br><br>
                <label for="total_installments">Total Installments:</label>
                <input type="text" id="total_installments" name="total_installments" value="<?php echo $total_installments; ?>" readonly><br><br>
                <label for="paid_installments">Paid Installments:</label>
                <input type="text" id="paid_installments" name="paid_installments" value="<?php echo $paid_installments; ?>"><br><br>
                <label for="remaining_installments">Remaining Installments:</label>
                <input type="text" id="remaining_installments" name="remaining_installments" value="<?php echo $remaining_installments; ?>" readonly><br><br>
                <label for="amount_to_be_paid">Amount to be Paid:</label>
                <input type="text" id="amount_to_be_paid" name="amount_to_be_paid" value="<?php echo $amount_to_be_paid; ?>" readonly><br><br>
                <?php if ($remaining_installments > 0) { ?>
                    <input type="submit" value="Pay Installment">
                <?php } else { ?>
                    <input type="submit" value="Pay Installment" disabled>
                <?php } ?>
            </form>

            <?php
            $conn2 = new mysqli($servername, $username, $password, $dbname);

            if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
            }

            $sql_transaction_history = "SELECT * FROM transactions WHERE member_id = $member_id AND done_by = 'Admin'";
            $result_transaction_history = $conn2->query($sql_transaction_history);

            if ($result_transaction_history->num_rows > 0) {
                echo "<h2>Transaction History</h2>";
                echo "<table>";
                echo "<tr><th>Date</th><th>Amount</th></tr>";
                while ($row_transaction = $result_transaction_history->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_transaction['date'] . " " . $row_transaction['time'] . "</td>";
                    echo "<td>" . $row_transaction['amount'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No transaction history found for this member.";
            }

            $conn2->close();
            ?>
        </div>
    <?php } ?>
    <div class="back-button-container">
        <button onclick="window.location.href='welcome.php';" class="back-button">
            <img src="https://img.icons8.com/metro/26/home.png" alt="home"/>
        </button>
    </div>
</body>
</html>
