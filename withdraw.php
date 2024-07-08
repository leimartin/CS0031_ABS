<?php include "connection.php" ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WITHDRAW</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="static/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.1/css/bulma.min.css">
</head>
<body>
   
<body class="overlay">
    <a href="index.php">
        <span class="icon-text">
            <span class="icon"><i class="fas fa-2x fa-home"></i></span>
            <p class="title is-4 has-text-link px-2">home</p>
        </span>
    </a>
    <section class="section is-medium">
        <div class="columns has-text-light">
         <div class="column is-4" >
            <h1 class="title is-1 has-text-light"><span class="has-text-info">Byte</span>Savings</h1>
        </div>

        <div class="column is-6 py-6">
                <h1 class="title is-2 has-text-light">Withdraw</h1>
            <div class="has-text-centered">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" autocomplete="off">
                    <div class="row text-center">
                        <input type="number" name="deposit-amount" class="input">
                        <label>AMOUNT</label> 
                    </div> <br/>

                    <input type="submit" name="submit" value="Withdraw Funds" class="button is-warning is-light has-text-weight-bold" style="font-size: 20px">
                    
                </form>
            </div>
        </div>
    </div>
</div>
</section>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            $AMOUNT = $_POST['deposit-amount'];
            $CARD_ID = $_SESSION['cardId'];

            $sth = "SELECT * FROM `clients_table` WHERE CARD_ID = ?";
            $sth_select = $conn->prepare($sth);
        
            if ($sth_select) {
                $sth_select->bind_param('s', $CARD_ID);
                $sth_select->execute();
                $result = $sth_select->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        if($row['INIT_DEPOSIT'] < $AMOUNT) {
                            echo '<script>';
                            echo 'alert("Insufficient Savings.\nACCOUNT NUMBER: ' . $CARD_ID . '\nBALANCE: ' .  $row['INIT_DEPOSIT'] . '");';
                            echo 'window.location.href = "index.php";';
                            echo '</script>';

                        } else {
                            $BALANCE = $row['INIT_DEPOSIT'] - $AMOUNT;
                            
                            $update = "UPDATE clients_table SET INIT_DEPOSIT = ? WHERE CARD_ID = ?";
                            $sth_update = $conn->prepare($update);

                            if ($sth_update) {
                                $sth_update->bind_param('is', $BALANCE, $CARD_ID);
                                $sth_update->execute();
                                $sth_update->close();

                                echo '<script>';
                                echo 'alert("Balance updated successfully!\nACCOUNT NUMBER: ' . $CARD_ID . '\nBALANCE: ' . $BALANCE . '");';
                                echo 'window.location.href = "index.php";';
                                echo '</script>';
                            
                                die();
                            } else {
                                echo "Update failed.";
                            }
                        }
                    }
            } else {
                echo "User not found";
            }
            
            $sth_select->close();
        } else {
            echo "Select failed.";
        }
    }
}
?>

</body>
</html>
