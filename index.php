<?php include "connection.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByteSavings</title>
    <link rel="stylesheet" href="static/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.1/css/bulma.min.css">
    <link rel="icon" type="image/x-icon" href="">
</head>
<body>

    <section class="section is-medium">
        <div class="columns has-text-centered has-text-light">
           <div class="column is-4">
            <h1 class="title is-2 has-text-light">Sign In</h1>

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

                    <div class="field">
                        <label class="label has-text-light">Card Number</label>
                        <div class="control has-icons-left ">
                            <input type="number" name="card-number" placeholder="202410000" class="input is-focused" required>
                            <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                        </div>
                    </div>


                    <div class="field">
                        <label class="label has-text-light">Account Pin</label>
                        <p class="control has-icons-left has-icons-right ">
                            <input type="password" name="password" id="password" class="input is-focused" placeholder="Pin">
                            <span class="icon is-small is-left"><i class="fa fa-lock"></i></span>
                            <span class="icon is-small is-right" style="pointer-events: all; cursor: pointer"><i class="fa fa-fw fa-eye-slash field_icon toggle-password"></i></span>
                        </p>
                    </div>

                    <label for="acc-type" class="label has-text-light">Account Type</label>
                
                        <div class="select is-fullwidth">
                            <select name="acc-type" required>
                                <option value="credit" selected>credit</option>
                                <option value="fixed">fixed</option>
                            </select>
                        </div>
                   

                    <div class="field mt-4">
                        <input type="submit" name="submit" value="LOG IN" class="button is-focused has-text-weight-bold">
                    </div>
                </form>

                <p class="p-3">Don't have an account? <a class="js-modal-trigger has-text-link" data-target="newUser" href="create.php">Sign Up</a></p>


            <?php } else {
                $sth = $conn->prepare("SELECT CARD_ID, SURNAME, FIRST_NAME, TYPE, INIT_DEPOSIT FROM `users_table` WHERE CARD_ID = ?");

                $sth->bind_param('s', $_SESSION['cardId']);
                $sth->execute();
                $result = $sth->get_result();

                if($result && $result->num_rows > 0) {
                    ?>


                    <table>
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
            <div style="border-left: 2px solid #333; height: 100%; margin: 20px;"></div>

            <div class="column is-4">
                <h1 class="title is-2 has-text-light p-3"><span class="has-text-info">Byte</span>Savings</h1>
                <p class="subtitle is-6 has-text-light">
                   At ByteSavings, we revolutionize the way you save and manage your money. Our user-friendly online banking platform is designed to help you maximize your savings with competitive interest rates and smart financial tools. Whether you're saving for a rainy day or planning for your future, ByteSavings offers the solutions you need to achieve your financial goals. Experience the convenience of digital banking with the security and support you deserve. Start your journey with ByteSavings today.
               </p>
           </div>

       </section>

       <script>
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('toggle-password')) {
                event.target.classList.toggle('fa-eye');
                event.target.classList.toggle('fa-eye-slash');
                var input = document.getElementById('password');
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            }
        });
    </script>
</body>
</html>


