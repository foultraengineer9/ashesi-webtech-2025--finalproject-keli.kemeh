<?php
// /settings/db_connect.php

// Localhost settings (Change these when moving to live server!)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mecha_lab_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Stop execution and show error if connection fails
    die("Connection failed: " . $conn->connect_error);
}
// Connection successful!
?>