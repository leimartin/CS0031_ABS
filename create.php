<?php require_once "connection.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="static/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.1/css/bulma.min.css">
</head>
<body>

    <section class="section is-small">
        <div class="columns has-text-centered has-text-light py-2">
            <div class="column is-4 p-5 m-5" >
                <h1 class="title is-2 has-text-light"><span class="has-text-info">Byte</span>Savings</h1>
                <p class="subtitle is-6 has-text-light">
                    At ByteSavings, we revolutionize the way you save and manage your money. Our user-friendly online banking platform is designed to help you maximize your savings with competitive interest rates and smart financial tools. Whether you're saving for a rainy day or planning for your future, ByteSavings offers the solutions you need to achieve your financial goals. Experience the convenience of digital banking with the security and support you deserve. Start your journey with ByteSavings today.
                </p>
            </div>

            <div class="column">

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
                            $stmt = $conn->prepare("INSERT INTO `clients_table` (`CARD_ID`, `SURNAME`, `FIRST_NAME`, `ADDRESS`, `TYPE`, `PIN`, `INIT_DEPOSIT`) VALUES (?, ?, ?, ?, ?, ?, ?);");
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

                    <form method="post" action="create.php" enctype="multipart/form-data" autocomplete="off">

                        <!-- Customer Name, Customer address, Required account type, Pin Number and Initial deposit. -->
                        <div class="columns">
                            <div class="column is-6">
                                <label class="label has-text-light">Surname</label>
                                <input type="text" name="user-surname" placeholder="Doe" class="input"  required>
                            </div>

                            <div class="column">
                                <label class="label has-text-light">First Name</label>
                                <input type="text" name="user-firstname" placeholder="Jane" class="input"  required>
                            </div>
                        </div>

                        <label class="label has-text-light">Address</label> 
                        <textarea name="user-address" id="" cols="20" rows="2" placeholder="123 Main Street, Anytown, USA 12345" class="textarea mt-3" required></textarea>


                        <label class="label has-text-light pt-4">Account Type:</label> 
                        <div class="select is-fullwidth">
                            <select name="account-type" required>
                                <option value="credit" selected>credit</option>
                                <option value="fixed">fixed</option>
                            </select>
                        </div>

                        <div class="columns pt-4">
                            <div class="column is-6">
                                <label class="label has-text-light" placeholder="1234">Pin</label> 
                                <p class="control has-icons-left has-icons-right ">
                                    <input type="password" name="user-pin" id="user-pin" class="input"  required>
                                    <span class="icon is-small is-left"><i class="fa fa-lock"></i></span>
                                    <span class="icon is-small is-right" style="pointer-events: all; cursor: pointer"><i class="fa fa-fw fa-eye-slash field_icon" onclick="toggle_password('user-pin')"></i></span>
                                </p>
                            </div>

                            <div class="column">
                                <label class="label has-text-light" placeholder="1234">Re-type Pin</label>
                                <p class="control has-icons-left has-icons-right ">
                                 <input type="password" name="retype-pin" id="retype-pin" class="input"  required>
                                 <span class="icon is-small is-left"><i class="fa fa-lock"></i></span>
                                 <span class="icon is-small is-right" style="pointer-events: all; cursor: pointer"><i class="fa fa-fw fa-eye-slash field_icon" onclick="toggle_password('retype-pin')"></i></span>
                             </p>
                         </div>
                     </div>


                     <div class="field">
                        <label class="label has-text-light">Initial Deposit</label> 
                        <input type="number" name="initial-deposit" class="input" required>
                    </div>


                    <center><input type="submit" name="submit" value="SIGN UP" class="button is-focused has-text-weight-bold my-2"></center>
                    <br>
                </form>
                <p class="">Already have an account? <a class="js-modal-trigger has-text-link" data-target="newUser" href="index.php">Login</a></p>

            <?php } ?>
        </div>
    </div>
</section>



<script>
 function toggle_password(inputId) {
    var input = document.getElementById(inputId);
    var icon = document.getElementById(`toggle${inputId.charAt(0).toUpperCase() + inputId.slice(1)}Icon`);

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>

</body>
</html>