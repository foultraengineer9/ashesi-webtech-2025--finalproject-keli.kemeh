<?php
// /actions/inventory_actions.php
// This file handles CREATE and DELETE operations remotely.

// 1. Connect to Database
require_once '../settings/db_connect.php';

// ==================== HANDLE CREATE (Add Item) ====================
if (isset($_POST['add_item_btn'])) {
    // Get data from form and escape special characters for security
    $name = mysqli_real_escape_string($conn, $_POST['c_name']);
    $category = mysqli_real_escape_string($conn, $_POST['c_category']);
    $serial = mysqli_real_escape_string($conn, $_POST['c_serial']);

    // Backend Regex Validation (Crucial backup for frontend JS)
    // Requirement: "The use of regular expression will be checked... do a PHP backend validation too"
    if (!preg_match("/^MECH-\d{4}-[A-Z]{2}$/", $serial)) {
        // If regex fails, stop and alert.
        echo "<script>alert('Backend Validation Failed: Invalid Serial Format!'); window.location.href='../index.php';</script>";
        exit();
    }

    // SQL Insert Statement
    $sql = "INSERT INTO Components (component_name, category, serial_number, status) 
            VALUES ('$name', '$category', '$serial', 'Available')";

    if ($conn->query($sql) === TRUE) {
        // Success: redirect back to dashboard
        header("Location: ../index.php?msg=added");
    } else {
        // Error handling (e.g., duplicate serial number)
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ==================== HANDLE DELETE ====================
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // Ensure ID is an integer for security

    // SQL Delete Statement
    $sql = "DELETE FROM Components WHERE component_id = $id";

    if ($conn->query($sql) === TRUE) {
        // Success: redirect back to dashboard
        header("Location: ../index.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>