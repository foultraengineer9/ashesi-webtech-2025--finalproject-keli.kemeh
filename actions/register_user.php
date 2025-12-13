<?php
// 1. Include Database Connection...
include "../settings/connection.php";

// 2. Check if button clicked...
if (isset($_POST['register_btn'])) {

    // 3 Collects and sanitize inputs...
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 
    $role = $_POST['role']; // Student or Admin

    // 4. Check if Email already exists...
    
    $check_query = "SELECT * FROM Users WHERE email = '$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        
        header("Location: ../register.php?msg=email_taken");
        exit();
    } else {
        // 5. Encrypt/Hash the Password...
        // password_hash() creates a secure, random hash...
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 6. Inserts User into Database...
        $sql = "INSERT INTO Users (fname, lname, email, password, role) 
                VALUES ('$fname', '$lname', '$email', '$hashed_password', '$role')";

        if ($conn->query($sql) === TRUE) {
            
            header("Location: ../index.php?msg=account_created");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    // For if accessed directly without form submission....
    header("Location: ../register.php");
    exit();
}
?>