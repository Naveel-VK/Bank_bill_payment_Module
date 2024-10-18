```php
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

// Retrieve member ID from URL
if (isset($_GET['member_id'])) {
    $member_id = sanitize_input($_GET['member_id']);

    // Retrieve transaction data for the specific member_id including type_of_account
    $sql_select_transactions = "SELECT * FROM transactions WHERE member_id = '$member_id'";
    $result_transactions = $conn->query($sql_select_transactions);

    // Close connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <style>
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

<div class="transaction-history">
    <h2>Transaction History for Member ID: <?php echo $member_id; ?></h2>
    <?php if ($result_transactions->num_rows > 0): ?>
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
    <p>No transactions found for Member ID: <?php echo $member_id; ?></p>
    <?php endif; ?>
</div>

<div class="back-button">
    <button onclick="window.location.href='member_details.php?member_id=<?php echo $member_id; ?>'">Back</button>
</div>

</body>
</html>

<?php
} else {
    echo "No member ID provided in the URL.";
}
?>
```