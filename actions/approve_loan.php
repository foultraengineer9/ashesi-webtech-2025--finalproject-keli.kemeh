<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_POST['approve_btn'])) {
    $loanId = intval($_POST['loan_id']);
    $dueDate = $_POST['due_date'];

    // Validate due date
    if (!$dueDate) {
        header("Location: ../admin/requests.php?msg=missing_due");
        exit();
    }

    // Only approve if pending
    $res = $conn->query("SELECT item_id FROM loans WHERE loan_id = $loanId AND status = 'Pending'");
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $itemId = intval($row['item_id']);

        // Conflict detection: prevent multiple approved for same item
        $conf = $conn->query("SELECT loan_id FROM loans WHERE item_id = $itemId AND status IN ('Approved','Borrowed')");
        if ($conf && $conf->num_rows > 0) {
            header("Location: ../admin/requests.php?msg=approve_conflict");
            exit();
        }

        // Update loan
        $stmt = $conn->prepare("UPDATE loans SET status='Approved', approve_date=NOW(), due_date=? WHERE loan_id=?");
        $stmt->bind_param("si", $dueDate, $loanId);
        if ($stmt->execute()) {
            header("Location: ../admin/requests.php?msg=approved");
            exit();
        }
    }
    header("Location: ../admin/requests.php?msg=approve_failed");
    exit();
} else {
    header("Location: ../admin/requests.php");
    exit();
}
?>
