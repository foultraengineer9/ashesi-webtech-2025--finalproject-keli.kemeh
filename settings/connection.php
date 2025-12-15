<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// settings/connection.php


// LOCALHOST CREDENTIALS (For local testing on my laptop)

 //$SERVER = "localhost";
 //$USERNAME = "root";
 //$PASSWORD = "";
 //$DATABASE = "mecha_lab_db";


// LIVE SERVER CREDENTIALS 

$SERVER = "sql202.infinityfree.com";
$USERNAME = "if0_40686913";
$PASSWORD = "KeKE1925";
$DATABASE = "if0_40686913_mecha_lab_db";

// Create Connection...
$conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);

// Checking Connection...
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//For Setting character set to handle special characters correctly...
$conn->set_charset("utf8");
?>