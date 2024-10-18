<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: black; 
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;


        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .member-details,
        .balance-details {
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .member-details p,
        .balance-details p {
            margin: 10px 0;
        }
        .transaction-history,
        .recurring-deposit {
            text-align: center;
            margin-bottom: 20px;
        }
        .custom-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 5px;
        }
        .custom-button:hover {
            background-color: #0056b3;
        }
        .card {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 280px;
            height: 150px;
            background: rgb(17, 4, 134);
            border-radius: 15px;
            box-shadow: 0px 0px 20px 5px rgba(255, 255, 255, 0.5);
            display: flex;
            color: #E6FF94;
            justify-content: center;
            flex-direction: column;
            background: linear-gradient(to right, rgb(20, 30, 48), rgb(36, 59, 85));
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            overflow: hidden;
        }
        .card:hover {
            box-shadow: rgb(0, 0, 0) 5px 10px 50px, rgb(0, 0, 0) -5px 0px 250px;
        }
        .time-text {
            font-size: 50px;
            margin-top: 0px;
            margin-left: 15px;
            font-weight: 600;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        .time-sub-text {
            font-size: 15px;
            margin-left: 5px;
        }
        .day-text {
            font-size: 18px;
            margin-top: 0px;
            margin-left: 15px;
            font-weight: 500;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        .back-button {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }
        .back-button img {
            width: 26px;
            height: 26px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();

        // Database connection
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

            if (isset($_POST['today_amount'])) {
                $today_amount = sanitize_input($_POST['today_amount']);

                $sql_update_balances = "UPDATE member_balances SET today_amount = today_amount + $today_amount, total_balance = total_balance + $today_amount WHERE member_id = $member_id";

                if ($conn->query($sql_update_balances) === TRUE) {
                    $amount = $today_amount;
                    $date = date('Y-m-d');
                    $time = date('H:i:s');
                    $done_by = "Some User";
                    $type_of_account = "Savings Account";

                    $sql_insert_transaction = "INSERT INTO transactions (member_id, amount, date, time, done_by, type_of_account) VALUES ($member_id, $amount, '$date', '$time', '$done_by', '$type_of_account')";
                    if ($conn->query($sql_insert_transaction) === TRUE) {
                        echo "Transaction recorded successfully";
                    } else {
                        echo "Error recording transaction: " . $conn->error;
                    }
                } else {
                    echo "Error updating balances: " . $conn->error;
                }
            }

            $sql_total_balance = "SELECT SUM(amount) AS total_balance FROM transactions WHERE member_id = $member_id";
            $result_total_balance = $conn->query($sql_total_balance);
            $row_total_balance = $result_total_balance->fetch_assoc();
            $total_balance = $row_total_balance['total_balance'];

            $sql_select = "SELECT * FROM members WHERE member_id = $member_id";
            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                <div class="member-details">
                    <p><strong>Member ID:</strong> <?php echo $row['member_id']; ?></p>
                    <p><strong>Name:</strong> <?php echo $row['name']; ?></p>
                    <p><strong>Account Number:</strong> <?php echo $row['account_no']; ?></p>
                    <p><strong>Address:</strong> <?php echo $row['address']; ?></p>
                    <p><strong>Class:</strong> <?php echo $row['class']; ?></p>
                </div>
                <div class="balance-details">
                    <h2>Balance Details</h2>
                    <p><strong>Total Balance:</strong> <?php echo $total_balance; ?></p>
                </div>

                <div class="transaction-history">
                    <button onclick="location.href='transaction_history.php?member_id=<?php echo $member_id; ?>';" type="button" class="custom-button">Transaction History</button>
                    <button onclick="location.href='recurring_deposit.php?member_id=<?php echo $member_id; ?>';" type="button" class="custom-button">Recurring Deposit</button>
                </div>

                <button onclick="window.location.href='welcome.php';" class="back-button">
                    <img src="https://img.icons8.com/metro/26/home.png" alt="home"/>
                </button>
                <?php
            } else {
                echo "Member ID not found";
            }
        } else {
            echo "No member ID provided";
        }

        $conn->close();
        ?>
    </div>

    <div class="card" id="clock">
        <p class="time-text"><span id="time"></span><span class="time-sub-text" id="period"></span></p>
        <p class="day-text" id="date"></p>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();
            const period = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 || 12;
            const formattedTime = `${formattedHours}:${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
            document.getElementById('time').textContent = formattedTime;
            document.getElementById('period').textContent = period;

            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const day = days[now.getDay()];
            const month = months[now.getMonth()];
            const date = now.getDate();
            const year = now.getFullYear();
            const formattedDate = `${day}, ${month} ${date}, ${year}`;
            document.getElementById('date').textContent = formattedDate;}

        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>