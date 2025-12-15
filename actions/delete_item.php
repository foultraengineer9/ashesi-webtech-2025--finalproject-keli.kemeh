<?php
include "../settings/connection.php";

// Checks if ID is present in the URL...
if (isset($_GET['id'])) {
    
    // 1. Sanitizes the ID (Security Best Practice)...
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Prevent deletion if there are active loans for this item
    $activeLoans = $conn->query("SELECT loan_id FROM loans WHERE item_id = '$id' AND status IN ('Pending','Approved','Borrowed')");
    if ($activeLoans && $activeLoans->num_rows > 0) {
        header("Location: ../admin/dashboard.php?msg=cannot_delete_active");
        exit();
    }

    // 2. Builds the Delete Query...
    $sql = "DELETE FROM inventory WHERE item_id = '$id'";

    // 3. Executes...
    if ($conn->query($sql) === TRUE) {
        // Success...
        header("Location: ../admin/dashboard.php?msg=deleted");
    } else {
        // Failure...
        echo "Error deleting record: " . $conn->error;
    }
} else {
    // If no ID provided, go back...
    header("Location: ../admin/dashboard.php");
}
?>
