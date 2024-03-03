
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
        <p>bank mo 2</p> 
    </div>
        
    <div class="container">
        <div class="row text-center">
            <div class="div-centered">
                <form method="post" action="create.php" enctype="multipart/form-data" autocomplete="off">

                <!-- Customer Name, Customer address, Required account type, Pin Number and Initial deposit. -->

                <input type="text" name="user-surname" placeholder="Doe" required>  <br />
                <label for="user-surname">Surname</label>  <br />

                <input type="text" name="user-firstname" placeholder="Jane" required> <br />
                <label for="user-firstname">First Name</label> <br />
                
                <textarea name="user-address" id="" cols="20" rows="4" placeholder="123 Main Street, Anytown, USA 12345" required></textarea><br />
                <label for="user-address">Address</label> <br />

                <label for="account-type">Account Type: </label>
                <select name="account-type"  required>
                    <option value="" disabled selected></option>
                    <option value="credit">credit</option>
                    <option value="fixed">fixed</option>
                </select> <br />

                <input type="password" name="user-pin" id="user-pin" required> 
                <input type="checkbox" id="toggle-user-pin" onchange="view('user-pin', 'toggle-user-pin')"> <br/>
                <label for="user-pin" placeholder="1234">Pin</label> <br />

                <input type="password" name="retype-pin" id="retype-pin" required>
                <input type="checkbox" id="toggle-retype" onchange="view('retype-pin', 'toggle-retype')">  <br/>
                <label for="retype-pin" placeholder="1234">Re-type Pin</label> <br />

                <input type="number" name="initial-deposit" required> <br />
                <label for="initial-deposit">Initial Deposit</label> <br />

                <input type="submit" name="submit">

                </form>
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
</html><?php 

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        if(isset($_POST['submit'])) {

            $SURNAME = $_POST['user-surname'];
            $FIRSTNAME = $_POST['user-firstname'];
            $ADDRESS = $_POST['user-address'];

            $ACCOUNT_TYPE = $_POST['account-type'];
            $TYPE_OPTION = ['credit', 'fixed'];

            $PIN = $_POST['user-pin'];
            $RETYPE_PIN = $_POST['retype-pin'];

            $INIT_DEPOSIT = $_POST['initial-deposit'];
        

            if(is_numeric($PIN)) {
                if (strlen($PIN) > 4) {
                    echo "<h2>Pin too long.</h2>";
                } else {
                    if(strlen($PIN) == strlen($RETYPE_PIN)) {
                        if(is_numeric($PIN) == is_numeric($RETYPE_PIN)) {
                            if($PIN == $RETYPE_PIN) {
                                echo "<h2>same pin</h2>";
                            } else {
                                echo "<h2>incorrect pin</h2>";
                            }
                        } else {
                            echo "<h2>Non-numeric pin.</h2>";
                        }
                    } else{
                        echo "<h2>different pin</h2>";
                    }
                }
            } else {
                echo "<h2>Non-numeric pin.</h2>";
            }



            // end
        }
        
    }

?>

