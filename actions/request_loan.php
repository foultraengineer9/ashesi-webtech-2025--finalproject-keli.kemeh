<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();

if (isset($_POST['request_btn'])) {
    $userId = $_SESSION['user_id'];
    $itemId = intval($_POST['item_id']);

    // Ensure item exists and is Available
    $itemRes = $conn->query("SELECT status FROM inventory WHERE item_id = $itemId");
    if ($itemRes && $itemRes->num_rows === 1) {
        $item = $itemRes->fetch_assoc();
        if ($item['status'] !== 'Available') {
            header("Location: ../view/dashboard.php?msg=item_unavailable");
            exit();
        }
    } else {
        header("Location: ../view/dashboard.php?msg=item_not_found");
        exit();
    }

    // Prevent duplicate pending/active requests by same user for same item
    $dupRes = $conn->query("SELECT loan_id FROM loans WHERE user_id = $userId AND item_id = $itemId AND status IN ('Pending','Approved','Borrowed')");
    if ($dupRes && $dupRes->num_rows > 0) {
        header("Location: ../view/dashboard.php?msg=already_requested");
        exit();
    }

    // Borrow limit: max 2 active loans (Approved/Borrowed)
    $limitRes = $conn->query("SELECT COUNT(*) AS c FROM loans WHERE user_id = $userId AND status IN ('Approved','Borrowed')");
    if ($limitRes && intval($limitRes->fetch_assoc()['c']) >= 2) {
        header("Location: ../view/dashboard.php?msg=borrow_limit");
        exit();
    }

    // Per-category limit: only 1 microcontroller at a time
    $catRes = $conn->query("SELECT category_id FROM inventory WHERE item_id = $itemId");
    if ($catRes && $catRes->num_rows === 1) {
        $catId = intval($catRes->fetch_assoc()['category_id']);
        if ($catId === 1) {
            $mcRes = $conn->query("SELECT COUNT(*) AS c FROM loans l JOIN inventory i ON i.item_id = l.item_id WHERE l.user_id = $userId AND i.category_id = 1 AND l.status IN ('Approved','Borrowed')");
            if ($mcRes && intval($mcRes->fetch_assoc()['c']) >= 1) {
                header("Location: ../view/dashboard.php?msg=category_limit");
                exit();
            }
        }
    }

    // Insert pending request
    $desiredStart = isset($_POST['desired_start_date']) ? $_POST['desired_start_date'] : null;
    $desiredEnd = isset($_POST['desired_end_date']) ? $_POST['desired_end_date'] : null;
    $stmt = $conn->prepare("INSERT INTO loans (item_id, user_id, status, request_date, desired_start_date, desired_end_date) VALUES (?, ?, 'Pending', NOW(), ?, ?)");
    $stmt->bind_param("iiss", $itemId, $userId, $desiredStart, $desiredEnd);
    if ($stmt->execute()) {
        header("Location: ../view/my_requests.php?msg=request_created");
        exit();
    } else {
        header("Location: ../view/dashboard.php?msg=request_failed");
        exit();
    }
} else {
    header("Location: ../view/dashboard.php");
    exit();
}
?>
