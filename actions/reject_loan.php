<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_GET['id'])) {
    $loanId = intval($_GET['id']);

    // Only reject if pending
    $res = $conn->query("SELECT loan_id FROM loans WHERE loan_id = $loanId AND status = 'Pending'");
    if ($res && $res->num_rows === 1) {
        if ($conn->query("UPDATE loans SET status = 'Rejected' WHERE loan_id = $loanId")) {
            header("Location: ../admin/requests.php?msg=rejected");
            exit();
        }
    }
    header("Location: ../admin/requests.php?msg=reject_failed");
    exit();
} else {
    header("Location: ../admin/requests.php");
    exit();
}
?>
