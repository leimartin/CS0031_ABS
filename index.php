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
    <p>WELCOME</p>
</div>


<div class="container">
    <div class="row">
        <div class="">
<?php


if(isset($_POST['submit'])) {
    $ACCOUNT_NUMBER = $_POST['card-number'];
    $USER_PIN = $_POST['pin'];

    $sth = $conn->prepare("SELECT CARD_ID, SURNAME, FIRST_NAME, TYPE, INIT_DEPOSIT FROM `users_table` WHERE CARD_ID = ? AND PIN = ?");
    $sth->bind_param('ss', $ACCOUNT_NUMBER, $USER_PIN);
    $sth->execute();
    $result = $sth->get_result();

    if($result && $result->num_rows > 0) {
        $_SESSION["cardId"] = $ACCOUNT_NUMBER;
    }
}

    if (!isset($_SESSION["cardId"])) {
?>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" autocomplete="off">
                <input type="number" name="card-number" placeholder="202410000" class="form-control" required>
                <label for="card-number">Card Number</label>

                <input type="password" name="pin" id="pin" class="form-control" required>
                <input type="checkbox" id="toggle-pin" onchange="view('pin', 'toggle-pin')" >
                <label for="user-pin" placeholder="1234">Show Pin</label> <br/>

                <label for="acc-type">Account Type</label>
                <select name="acc-type" class="btn btn-primary dropdown-toggle" required>
                    <option value="credit" selected>credit</option>
                    <option value="fixed">fixed</option>
                </select> <br /><br />

                <center><input type="submit" name="submit" value="LOGIN" class="btn btn-info"></center>
            </form><br />

            <!-- creating an account -->
            <span>Don't have an account? <a href="create.php">Sign up</a></span>
<?php } else {
    $sth = $conn->prepare("SELECT CARD_ID, SURNAME, FIRST_NAME, TYPE, INIT_DEPOSIT FROM `users_table` WHERE CARD_ID = ?");

    $sth->bind_param('s', $_SESSION['cardId']);
    $sth->execute();
    $result = $sth->get_result();

    if($result && $result->num_rows > 0) {
    ?>


    <table>
<!--        <tr>-->
<!--            <th>CARD ID</th>-->
<!--            <th>SURNAME</th>-->
<!--            <th>FIRST NAME</th>-->
<!--            <th>TYPE</th>-->
<!--            <th>BALANCE</th>-->
<!--        </tr>-->
        <?php
        while($row = $result->fetch_assoc()) {
            ?>
                <div class="row">
                    <div class="col-sm-4 text-end" style="background-color:lavender; font-weight: bold; font-size: 1.5em; height: 32px">
                            <?php echo $row['CARD_ID'] . " [" . $row['TYPE'] . "]" ; ?>
                    </div>

                    <div class="col-sm-8 text-end" style="background-color:lavender; font-weight: bold; font-size: 1.5em; height: 32px; overflow: hidden">
                        <div class="row text-end" style="text-align: right">
                            <?php echo "Hi, " . $row['FIRST_NAME'] . " ". $row['SURNAME']; ?>
                            <button class="btn btn-dark" style="text-decoration: none"><a href="logout.php" >Logout</a></button>
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <h1 style="font-size: 5em; font-weight: bold">₱ <?php echo number_format($row['INIT_DEPOSIT'], 2); ?></h1>
                    <label for="">BALANCE</label>
                </div> <br/>

            <?php
        }
        ?>
    </table>

    <div class="row text-center">
        <button class="btn btn-success"><a style="text-decoration: none; color: white; font-size: 30px" href="deposit.php">Deposit</a></button>
        <button class="btn btn-danger"><a style="text-decoration: none; color: white; font-size: 30px" href="withdraw.php">Withdraw</a></button>
    </div>

<?php } } ?>
        </div>
    </div>

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


