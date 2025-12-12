<?php
session_start();
include '../settings/db_connect.php';

if (isset($_POST['register_btn'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    // 1. Check if passwords match
    if ($pass !== $cpass) {
        echo "<script>alert('Passwords do not match!'); window.location.href='../register.php';</script>";
        exit();
    }

    // 2. Check if email already exists
    $check_email = "SELECT email FROM Users WHERE email='$email'";
    $result = $conn->query($check_email);
    if ($result->num_rows > 0) {
        echo "<script>alert('Email already used!'); window.location.href='../register.php';</script>";
        exit();
    }

    // 3. Hash the password (SECURITY BEST PRACTICE)
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // 4. Insert User
    // Default role is 'student'. You can manually change one user to 'admin' in phpMyAdmin later.
    $sql = "INSERT INTO Users (fullname, email, password, user_role) VALUES ('$fname', '$email', '$hashed_password', 'student')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful! Please Login.'); window.location.href='../login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>