<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="inventory_export.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['item_name','serial_number','category_id','description','status']);
$res = $conn->query("SELECT item_name, serial_number, category_id, description, status FROM inventory");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        fputcsv($out, $row);
    }
}
fclose($out);
exit();
?>
