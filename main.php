<?php include "connection.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTOMATED BANK SYSTEM</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

    <div class="jumbotron text-center">
        <h1>AUTOMATED BANK SYSTEM</h1>
        <p>eksdeh sa bank mo 2</p> 
    </div>
        
    <div class="container">
        <div class="row text-center">
            <div class="div-centered">
                    
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" autocomplete="off">
                    <input type="number" name="card-number" placeholder="202410000" required>  <br />
                    <label for="card-number">Card Number</label>  <br />
                                    
                    <input type="password" name="pin" id="pin" required>
                    <input type="checkbox" id="toggle-pin" onchange="view('pin', 'toggle-pin')"> <br/>
                    <label for="user-pin" placeholder="1234">Pin</label> <br />
                                        
                    <label for="acc-type">Account Type</label>
                    <select name="acc-type" required>
                        <option value="" disabled selected></option>
                        <option value="credit">credit</option>
                        <option value="fixed">fixed</option>
                    </select> <br />

                    <center><input type="submit" name="submit"></center>
                </form>
                    
                <!-- creating an account -->
                <span>Don't have an account? <a href="create.php">Sign up</a></span>
            </div>
        </div>
    </div>


<?php 
    if(isset($_POST['submit'])) {
        $ACCOUNT_NUMBER = $_POST['card-number'];
        $USER_PIN = $_POST['pin'];
        $sth = $conn->prepare("SELECT CARD_ID, SURNAME, FIRST_NAME, TYPE, INIT_DEPOSIT FROM `users_table` WHERE CARD_ID = ? AND PIN = ?");

        if ($sth) {
            $sth->bind_param('ss', $ACCOUNT_NUMBER, $USER_PIN);
            $sth->execute();
            $result = $sth->get_result();

            if($result && $result->num_rows > 0) {
            ?>
                <table>
                    <tr>
                        <th>CARD ID</th>
                        <th>SURNAME</th>
                        <th>FIRST NAME</th>
                        <th>TYPE</th>
                        <th>BALANCE</th>
                    </tr>
                    <?php 
                    while($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $row['CARD_ID']; ?></td>
                            <td><?php echo $row['SURNAME']; ?></td>
                            <td><?php echo $row['FIRST_NAME']; ?></td>
                            <td><?php echo $row['TYPE']; ?></td>
                            <td><?php echo $row['INIT_DEPOSIT']; ?></td> 
                        </tr>
                    <?php 
                    } 
                    ?>
                </table>

                <a href="deposit.php">Deposit</a>
                <a href="withdraw.php">Withdraw</a>

            <?php           
            } else {
                echo "Account does not exist";
            } 
            $sth->close();
        }
    }
?>

    <script>
        function view(pin_id, toggle_id) {
            const pin = document.getElementById(pin_id);
            const toggle_pin = document.getElementById(toggle_id);

            pin.type = toggle_pin.checked ? "text" : "password";
        }
    </script>
</body>
</html>


