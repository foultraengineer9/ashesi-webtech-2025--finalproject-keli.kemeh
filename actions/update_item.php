<?php
include "../settings/connection.php";

if (isset($_POST['update_btn'])) {
    // 1. Collects Data...
    $id = $_POST['item_id']; // This comes from the hidden input
    $name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $serial = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $category = $_POST['category'];
    $status = $_POST['status'];

    // 2. Updates Query...
    $sql = "UPDATE Inventory SET 
            item_name = '$name', 
            serial_number = '$serial', 
            category_id = '$category', 
            status = '$status' 
            WHERE item_id = '$id'";

    // 3. Executes...
    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin/dashboard.php?msg=updated");
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    header("Location: ../admin/dashboard.php");
}
?>