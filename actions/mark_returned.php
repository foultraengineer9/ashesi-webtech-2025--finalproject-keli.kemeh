<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

if (isset($_GET['id'])) {
    $loanId = intval($_GET['id']);

    // Move Borrowed -> Returned and update inventory status
    $res = $conn->query("SELECT item_id FROM loans WHERE loan_id = $loanId AND status = 'Borrowed'");
    if ($res && $res->num_rows === 1) {
        $itemId = intval($res->fetch_assoc()['item_id']);
        $conn->begin_transaction();
        try {
            // Compute overdue days and fines
            $loanQ = $conn->query("SELECT due_date FROM loans WHERE loan_id = $loanId");
            $overdueDays = 0;
            if ($loanQ && $loanQ->num_rows === 1) {
                $due = $loanQ->fetch_assoc()['due_date'];
                if ($due) {
                    $dueTs = strtotime($due);
                    $nowTs = time();
                    if ($nowTs > $dueTs) {
                        $overdueDays = floor(($nowTs - $dueTs) / 86400);
                    }
                }
            }
            $conn->query("UPDATE loans SET status = 'Returned', return_date = NOW() WHERE loan_id = $loanId");
            $conn->query("UPDATE inventory SET status = 'Available' WHERE item_id = $itemId");
            if ($overdueDays > 0) {
                // Simple policy: GHS 5 per overdue day and +1 strike
                $conn->query("UPDATE users u JOIN loans l ON l.user_id = u.user_id SET u.fines = u.fines + (" . ($overdueDays * 5) . "), u.strikes = u.strikes + 1 WHERE l.loan_id = $loanId");
            }
            $conn->commit();
            header("Location: ../admin/requests.php?msg=returned");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
        }
    }
    header("Location: ../admin/requests.php?msg=return_failed");
    exit();
} else {
    header("Location: ../admin/requests.php");
    exit();
}
?>
