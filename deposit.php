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
        <p>deposit k muna bhe</p> 
    </div>

    <div class="container">
        <div class="row text-center">
            <div class="div-centered">
			    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" autocomplete="off">
			        <input type="number" name="card-number"> <br/>
			        <label>CARD ID</label> <br/>

			        <input type="number" name="deposit-amount"> <br/>
			        <label>AMOUNT</label> <br/>

			        <input type="submit" name="submit"> 
			    </form>
			</div>
		</div>
	</div>

<?php

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	if (isset($_POST['submit'])) {
        	$AMOUNT = $_POST['deposit-amount'];
        	$CARD_ID = $_POST['card-number'];
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
							echo 'window.location.href = "main.php";';
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
