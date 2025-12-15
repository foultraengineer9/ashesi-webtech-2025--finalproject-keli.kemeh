<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_POST['approve_renewal_btn'])) {
    $loanId = intval($_POST['loan_id']);
    $newDue = $_POST['new_due_date'];

    $res = $conn->query("SELECT renewals_count, max_renewals FROM loans WHERE loan_id = $loanId AND status = 'Borrowed' AND renewal_requested = 1");
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (intval($row['renewals_count']) >= intval($row['max_renewals'])) {
            header("Location: ../admin/requests.php?msg=renewal_limit");
            exit();
        }
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("UPDATE loans SET due_date = ?, renewals_count = renewals_count + 1, renewal_requested = 0 WHERE loan_id = ?");
            $stmt->bind_param("si", $newDue, $loanId);
            $stmt->execute();
            $conn->commit();
            header("Location: ../admin/requests.php?msg=renewal_approved");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
        }
    }
    header("Location: ../admin/requests.php?msg=renewal_failed");
    exit();
} else {
    header("Location: ../admin/requests.php");
    exit();
}
?>
