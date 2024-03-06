<?php include "connection.php" ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DEPOSIT</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
	<div class="jumbotron text-center">
        <h1>AUTOMATED BANK SYSTEM</h1>
        <p>DEPOSIT</p>
    </div>

    <div class="container">
        <div class="row" style="padding-left: 25vw; padding-bottom: 5vh">
            <a href="index.php"><button class="btn btn-primary">< BACK</button></a>
        </div>
        <div class="row text-center">
            <div class="div-centered">
			    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" autocomplete="off">
                    <div class="row text-center">
                        <input type="number" name="deposit-amount" class="form-control">
                        <label style="justify-content: center;">AMOUNT</label>
                    </div> <br/>

                    <div class="row text-center">
                        <input type="submit" name="submit" value="ADD FUNDS" class="btn btn-success" style="font-size: 20px">
                    </div>
                    <br>



			    </form>
			</div>
		</div>
	</div>

<?php

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	if (isset($_POST['submit'])) {
        	$AMOUNT = $_POST['deposit-amount'];
        	$CARD_ID = $_SESSION['cardId'];
        	$sth = "SELECT * FROM `users_table` WHERE CARD_ID = ?";

        	$sth_select = $conn->prepare($sth);
        
        	if ($sth_select) {
            	$sth_select->bind_param('s', $CARD_ID);
            	$sth_select->execute();
            	$result = $sth_select->get_result();

            	if ($result && $result->num_rows > 0) {
                	while ($row = $result->fetch_assoc()) {
                    	$BALANCE = $row['INIT_DEPOSIT'] + $AMOUNT;
                    	// echo "<center>Curent Balance: " . $BALANCE . "<center>";

                    	$update = "UPDATE users_table SET INIT_DEPOSIT = ? WHERE CARD_ID = ?";
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
