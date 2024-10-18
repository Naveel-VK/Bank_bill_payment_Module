<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banks2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if date is selected
$selected_date = '';
if (isset($_POST['selected_date'])) {
    $selected_date = sanitize_input($_POST['selected_date']);
}

// Retrieve transactions for the selected date
if ($selected_date) {
    $sql_select_transactions = "SELECT * FROM transactions WHERE date = '$selected_date'";
    $result_transactions = $conn->query($sql_select_transactions);
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report</title>
    <style>
        .calendar-container {
            margin: 20px;
            text-align: center;
        }
        .calendar-container input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .transaction-history {
            margin-top: 20px;
        }
        .transaction-history table {
            width: 100%;
            border-collapse: collapse;
        }
        .transaction-history table th,
        .transaction-history table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .transaction-history table th {
            background-color: #f2f2f2;
        }
        .back-button {
            margin-top: 20px;
            text-align: center;
        }
        .back-button button {
            background-color: navy;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .back-button button:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>

<div class="calendar-container">
    <h2>Select Date to Generate Report</h2>
    <form method="post" action="report.php">
        <input type="date" name="selected_date" value="<?php echo $selected_date; ?>">
        <button type="submit">Generate Report</button>
    </form>
</div>

<?php if ($selected_date): ?>
<div class="transaction-history">
    <h2>Transaction History for Date: <?php echo $selected_date; ?></h2>
    <?php if (isset($result_transactions) && $result_transactions->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Member ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Time</th>
                <th>Done By</th>
                <th>Type of Account</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_transactions->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['transaction_id']; ?></td>
                <td><?php echo $row['member_id']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $_SESSION['username']; ?></td>
                <td><?php echo $row['type_of_account']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No transactions found for Date: <?php echo $selected_date; ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="back-button">
    <button onclick="window.location.href='welcome.php'">Back</button>
</div>

</body>
</html>
