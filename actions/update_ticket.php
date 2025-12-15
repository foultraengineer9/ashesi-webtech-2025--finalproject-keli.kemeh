<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_POST['update_ticket_btn'])) {
    $id = intval($_POST['ticket_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $stmt = $conn->prepare("UPDATE maintenance_tickets SET status = ?, updated_at = NOW() WHERE ticket_id = ?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        header("Location: ../admin/maintenance.php?msg=ticket_updated");
        exit();
    }
}
header("Location: ../admin/maintenance.php?msg=ticket_failed");
exit();
?>
