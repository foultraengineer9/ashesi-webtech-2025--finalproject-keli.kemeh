<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_POST['import_btn']) && isset($_FILES['csv'])) {
    $tmp = $_FILES['csv']['tmp_name'];
    if (!is_uploaded_file($tmp)) {
        header("Location: ../admin/import_export.php?msg=import_failed");
        exit();
    }
    $handle = fopen($tmp, 'r');
    if (!$handle) {
        header("Location: ../admin/import_export.php?msg=import_failed");
        exit();
    }
    // Skip header
    fgetcsv($handle);
    $count = 0;
    while (($data = fgetcsv($handle)) !== false) {
        if (count($data) < 5) continue;
        [$name,$serial,$category,$description,$status] = $data;
        $name = mysqli_real_escape_string($conn, $name);
        $serial = mysqli_real_escape_string($conn, $serial);
        $category = intval($category);
        $description = mysqli_real_escape_string($conn, $description);
        $status = mysqli_real_escape_string($conn, $status);
        // skip if serial exists
        $exists = $conn->query("SELECT item_id FROM inventory WHERE serial_number = '$serial'");
        if ($exists && $exists->num_rows > 0) continue;
        $conn->query("INSERT INTO inventory (item_name, serial_number, category_id, description, status) VALUES ('$name','$serial',$category,'$description','$status')");
        $count++;
    }
    fclose($handle);
    header("Location: ../admin/import_export.php?msg=import_ok&count=$count");
    exit();
}
header("Location: ../admin/import_export.php");
exit();
?>
