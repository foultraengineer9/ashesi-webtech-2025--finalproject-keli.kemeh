<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_GET['id'])) {
    $loanId = intval($_GET['id']);

    // Move Approved -> Borrowed and update inventory status
    $res = $conn->query("SELECT item_id FROM loans WHERE loan_id = $loanId AND status = 'Approved'");
    if ($res && $res->num_rows === 1) {
        $itemId = intval($res->fetch_assoc()['item_id']);
        $conn->begin_transaction();
        try {
            $conn->query("UPDATE loans SET status = 'Borrowed' WHERE loan_id = $loanId");
            $conn->query("UPDATE inventory SET status = 'Borrowed' WHERE item_id = $itemId");
            $conn->commit();
            header("Location: ../admin/requests.php?msg=borrowed");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
        }
    }
    header("Location: ../admin/requests.php?msg=borrow_failed");
    exit();
} else {
    header("Location: ../admin/requests.php");
    exit();
}
?>
