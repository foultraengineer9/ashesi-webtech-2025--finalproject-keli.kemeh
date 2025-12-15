<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_POST['add_category_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        header("Location: ../admin/categories.php?msg=cat_added");
        exit();
    }
}
header("Location: ../admin/categories.php?msg=cat_failed");
exit();
?>
