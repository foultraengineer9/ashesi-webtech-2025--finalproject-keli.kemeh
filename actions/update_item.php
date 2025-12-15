<?php
include "../settings/connection.php";

if (isset($_POST['update_btn'])) {
    // 1. Collects Data...
    $id = $_POST['item_id']; // This comes from the hidden input
    $name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $serial = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $category = $_POST['category'];
    $status = $_POST['status'];

    // Unique serial validation excluding current item
    $check = $conn->query("SELECT item_id FROM inventory WHERE serial_number = '$serial' AND item_id <> '$id'");
    if ($check && $check->num_rows > 0) {
        header("Location: ../admin/edit_inventory.php?id=$id&msg=serial_exists");
        exit();
    }

    // 2. Updates Query...
    $sql = "UPDATE inventory SET 
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
