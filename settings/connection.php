<?php
// settings/connection.php


// LOCALHOST CREDENTIALS (For local testing on my laptop)

// $SERVER = "localhost";
// $USERNAME = "root";
// $PASSWORD = ""; 
// $DATABASE = "mecha_lab_db";


// LIVE SERVER CREDENTIALS 

$SERVER = "localhost";   // Often this stays "localhost" on cPanel, or an IP address/URL on Azure
$USERNAME = "YOUR_LIVE_USERNAME"; 
$PASSWORD = "YOUR_LIVE_PASSWORD"; 
$DATABASE = "YOUR_LIVE_DB_NAME";

// Create Connection...
$conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);

// Checking Connection...
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//For Setting character set to handle special characters correctly...
$conn->set_charset("utf8");
?>