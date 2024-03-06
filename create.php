<?php require_once "connection.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="jumbotron text-center">
    <h1>AUTOMATED BANK SYSTEM</h1>
    <p>SIGN UP FORM</p>
</div>

<div class="container">
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit'])) {

        $SURNAME = $_POST['user-surname'];
        $FIRSTNAME = $_POST['user-firstname'];
        $ADDRESS = $_POST['user-address'];

        $ACCOUNT_TYPE = $_POST['account-type'];
        $TYPE_OPTION = ['credit', 'fixed'];

        $INIT_DEPOSIT = $_POST['initial-deposit'];


        $PIN = $_POST['user-pin'];
        $RETYPE_PIN = $_POST['retype-pin'];


        function verifyPIN()
        {
            $PIN = $_POST['user-pin'];
            $RETYPE_PIN = $_POST['retype-pin'];
            if (!is_numeric($PIN))
                return "<h2>Non-numeric pin.</h2>";
            if (strlen($PIN) > 6)
                return "<h2>Pin too long.</h2>";
            if (strlen($PIN) < 4)
                return "<h2>Pin too short.</h2>";
            if ($PIN != $RETYPE_PIN)
                return "<h2>different pin</h2>";
            return "";
        }

        if ($error = verifyPIN()) {
            echo $error . "<br/>Redirecting in 3 seconds...";

            header( "refresh:3;url=create.php" );
        } else {
            $stmt = $conn->prepare("INSERT INTO `users_table` (`CARD_ID`, `SURNAME`, `FIRST_NAME`, `ADDRESS`, `TYPE`, `PIN`, `INIT_DEPOSIT`) VALUES (?, ?, ?, ?, ?, ?, ?);");
            $cardId = intval("2024" . mt_rand(10000, 99999));
            $stmt->bind_param("issssii", $cardId, $SURNAME, $FIRSTNAME, $ADDRESS, $ACCOUNT_TYPE, $PIN, $INIT_DEPOSIT);
            $stmt->execute();
            $stmt->close();

            echo "<h2>Your Card ID is: $cardId</h2><p>Please take note of this ID, the page will reload in 30 seconds for your security.</p><a href='index.php'>Click here to login</a>";
            header( "refresh:30;url=index.php");
        }
    }

} else {

?>
        <div class="row text-start">
            <div class="">

                <form method="post" action="create.php" enctype="multipart/form-data" autocomplete="off">

                    <!-- Customer Name, Customer address, Required account type, Pin Number and Initial deposit. -->

                    <input type="text" name="user-surname" placeholder="Doe" class="form-control"  required>
                    <label for="user-surname">Surname</label> <br/>

                    <input type="text" name="user-firstname" placeholder="Jane" class="form-control"  required>
                    <label for="user-firstname">First Name</label> <br/>

                    <textarea name="user-address" id="" cols="20" rows="4" placeholder="123 Main Street, Anytown, USA 12345"
                              class="form-control"  required></textarea>
                    <label for="user-address">Address</label> <br/>

                    <label for="account-type">Account Type: </label>
                    <select name="account-type" class="btn btn-primary dropdown-toggle"  required>
                        <option value="credit" selected>credit</option>
                        <option value="fixed">fixed</option>
                    </select> <br/>

                    <input type="password" name="user-pin" id="user-pin" class="form-control"  required>show pin
                    <input type="checkbox" id="toggle-user-pin" onchange="view('user-pin', 'toggle-user-pin')" > <br/>
                    <label for="user-pin" placeholder="1234">Pin</label> <br/>

                    <input type="password" name="retype-pin" id="retype-pin" class="form-control"  required>show pin
                    <input type="checkbox" id="toggle-retype" onchange="view('retype-pin', 'toggle-retype')"> <br/>
                    <label for="retype-pin" placeholder="1234">Re-type Pin</label> <br/>

                    <input type="number" name="initial-deposit" class="form-control" required>
                    <label for="initial-deposit">Initial Deposit</label> <br/>

                    <center><input type="submit" name="submit" value="SIGN UP" class="btn btn-info"></center>
                    <br>

                </form>
                Already have an account? <a href="index.php">Login</a>
            </div>
        </div>

<?php } ?>


</div>

<script>
    function view(pin_id, toggle_id) {
        const pin = document.getElementById(pin_id);
        const toggle_pin = document.getElementById(toggle_id);

        pin.type = toggle_pin.checked ? "text" : "password";
    }
</script>

</body>
</html>