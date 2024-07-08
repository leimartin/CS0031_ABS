<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'library';

	$conn = new mysqli($servername, $username, $password, $database);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

    session_start();

    $clients_query = "CREATE TABLE clients_table (
        CARD_ID INT PRIMARY KEY,
	    SURNAME VARCHAR(255) NOT NULL,
	    FIRST_NAME VARCHAR(255) NOT NULL,
	    ADDRESS VARCHAR(255),
	    TYPE VARCHAR(50) NOT NULL,
	    PIN VARCHAR(255) NOT NULL,
	    INIT_DEPOSIT DECIMAL(10, 2) NOT NULL,
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

   /* if ($conn->query($clients_query) === TRUE ) {
    	echo '<script>console.log("users table created successfully.")</script>';
	}*/

	$transactions_query = "CREATE TABLE transactions_table (
	    ID INT AUTO_INCREMENT PRIMARY KEY,
	    CLIENT_ID INT,
	    TRANSACTION_TYPE INT(11) NOT NULL,
	    CURRENT_BALANCE DECIMAL(10, 2) NOT NULL,
	    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	    FOREIGN KEY (CLIENT_ID) REFERENCES clients_table(CARD_ID)
	 )";

	/*if ($conn->query($transactions_query) === TRUE) {
    echo '<script>console.log("Transaction table created successfully.")</script>';
} else {
    echo '<script>console.error("Error creating transaction table: ' . $conn->error . '")</script>';
}*/
	
?>