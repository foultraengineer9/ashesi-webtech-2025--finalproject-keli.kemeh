<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_POST['update_category_btn'])) {
    $id = intval($_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $stmt = $conn->prepare("UPDATE categories SET category_name = ? WHERE category_id = ?");
    $stmt->bind_param("si", $name, $id);
    if ($stmt->execute()) {
        header("Location: ../admin/categories.php?msg=cat_updated");
        exit();
    }
}
header("Location: ../admin/categories.php?msg=cat_failed");
exit();
?>
