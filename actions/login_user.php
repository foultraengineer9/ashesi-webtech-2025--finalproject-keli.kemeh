<?php
session_start();
include '../settings/db_connect.php';

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 1. Check if user exists
    $sql = "SELECT * FROM Users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // 2. Verify Password
        if (password_verify($password, $row['password'])) {
            // Password Correct: Set Session Variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['user_role'];

            // Redirect to Dashboard
            header("Location: ../index.php");
            exit();
        } else {
            // Password Wrong
            echo "<script>alert('Incorrect Password!'); window.location.href='../login.php';</script>";
        }
    } else {
        // User not found
        echo "<script>alert('User not found!'); window.location.href='../register.php';</script>";
    }
}
?>