<?php


session_start();

// the fxn to check if user is logged in
// It is used on pages like dashboard.php to block unauthorized access
function check_login() {
    // If the 'user_id' session variable is not set, redirect to login...
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php"); 
        //We use ../ because this function will be called from inside the 'view' or 'admin' folders...
        die();
    }
}

// Function to get user ID (Helper function)...
function get_user_id() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else {
        return false;
    }
}

// Function to check for Admin Role...
function check_admin_role() {
    // If role is not set or not Admin, redirect or stop...
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
        // Redirect to student dashboard or show error...
        header("Location: ../view/dashboard_student.php");
        die();
    }
}
?>