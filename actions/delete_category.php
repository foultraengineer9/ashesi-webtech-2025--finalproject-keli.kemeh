<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Prevent delete if items exist in category
    $res = $conn->query("SELECT item_id FROM inventory WHERE category_id = $id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        header("Location: ../admin/categories.php?msg=cat_in_use");
        exit();
    }
    if ($conn->query("DELETE FROM categories WHERE category_id = $id")) {
        header("Location: ../admin/categories.php?msg=cat_deleted");
        exit();
    }
}
header("Location: ../admin/categories.php?msg=cat_failed");
exit();
?>
