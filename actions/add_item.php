<?php
include "../settings/connection.php";

if (isset($_POST['add_item_btn'])) {
    // 1. Collecting Data...
    $name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $serial = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $category = $_POST['category'];
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $status = 'Available'; 

    // 2. Prepares SQL...
    $sql = "INSERT INTO inventory (category_id, item_name, serial_number, description, status) 
            VALUES ('$category', '$name', '$serial', '$desc', '$status')";

    // 3. Execute and Check...
    if ($conn->query($sql) === TRUE) {
        // Success: Go back to dashboard
        header("Location: ../admin/dashboard.php?msg=success");
    } else {
        // Error: Shows what happened...
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Redirects if accessed directly...
    header("Location: ../admin/dashboard.php");
}
?>