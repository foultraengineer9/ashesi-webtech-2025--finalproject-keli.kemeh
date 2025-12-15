<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();

if (isset($_GET['id'])) {
    $loanId = intval($_GET['id']);
    $userId = $_SESSION['user_id'];

    // Only allow cancel if it's pending and belongs to user
    $res = $conn->query("SELECT loan_id FROM loans WHERE loan_id = $loanId AND user_id = $userId AND status = 'Pending'");
    if ($res && $res->num_rows === 1) {
        // Delete or mark rejected; choose rejected to retain history
        if ($conn->query("UPDATE loans SET status = 'Rejected' WHERE loan_id = $loanId")) {
            header("Location: ../view/my_requests.php?msg=request_cancelled");
            exit();
        }
    }
    header("Location: ../view/my_requests.php?msg=cancel_failed");
    exit();
} else {
    header("Location: ../view/my_requests.php");
    exit();
}
?>
