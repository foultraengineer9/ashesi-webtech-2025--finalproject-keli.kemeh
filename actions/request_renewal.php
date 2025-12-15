<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();

if (isset($_GET['id'])) {
    $loanId = intval($_GET['id']);
    $userId = $_SESSION['user_id'];

    // Only allow renewal for own Borrowed loans and within max_renewals
    $res = $conn->query("SELECT renewals_count, max_renewals, renewal_requested FROM loans WHERE loan_id = $loanId AND user_id = $userId AND status = 'Borrowed'");
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (intval($row['renewals_count']) >= intval($row['max_renewals'])) {
            header("Location: ../view/my_requests.php?msg=renewal_limit");
            exit();
        }
        if (intval($row['renewal_requested']) === 1) {
            header("Location: ../view/my_requests.php?msg=renewal_pending");
            exit();
        }
        if ($conn->query("UPDATE loans SET renewal_requested = 1 WHERE loan_id = $loanId")) {
            header("Location: ../view/my_requests.php?msg=renewal_requested");
            exit();
        }
    }
    header("Location: ../view/my_requests.php?msg=renewal_failed");
    exit();
} else {
    header("Location: ../view/my_requests.php");
    exit();
}
?>
