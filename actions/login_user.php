<?php
// 1. Starts Session & Connect to DB...
session_start();
include "../settings/connection.php";

// 2. Checks if the login button was clicked...
if (isset($_POST['login_btn'])) {

    // 3. Collects and cleans form data...
    $email = mysqli_real_escape_string($conn, $_POST['email']); 
    $password = $_POST['password']; 

    // Writes Query to check if email exists...
    $sql = "SELECT * FROM Users WHERE email = '$email'";
    $result = $conn->query($sql);

    // 5. Checks if we found a user...
    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        $hashed_password_from_db = $row['password'];

        // 6. Verifyies the Password...
        // precise password verification using PHP's built-in secure function..
        if (password_verify($password, $hashed_password_from_db)) {
            
            
            // Sets Session Variables...
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role']; 
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['lname'] = $row['lname'];

            // 7. Redirects based on Role (Admin vs Student)...
            if ($row['role'] == 'Admin') {
                header("Location: ../admin/dashboard.php"); 
            } else {
                header("Location: ../view/dashboard.php");  
            }
            exit();

        } else {
            
            header("Location: ../index.php?msg=incorrect_password");
            exit();
        }

    } else {
        
        header("Location: ../index.php?msg=account_not_found");
        exit();
    }
} else {
    
    header("Location: ../index.php");
    exit();
}
?>
