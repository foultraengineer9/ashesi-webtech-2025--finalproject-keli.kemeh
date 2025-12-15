<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();

if (isset($_POST['change_password_btn'])) {
    $userId = $_SESSION['user_id'];
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        header("Location: ../view/profile.php?msg=pw_mismatch");
        exit();
    }

    $res = $conn->query("SELECT password FROM users WHERE user_id = $userId");
    if ($res && $res->num_rows === 1) {
        $hash = $res->fetch_assoc()['password'];
        if (!password_verify($current, $hash)) {
            header("Location: ../view/profile.php?msg=pw_incorrect");
            exit();
        }
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $newHash, $userId);
        if ($stmt->execute()) {
            header("Location: ../view/profile.php?msg=pw_changed");
            exit();
        }
    }
}
header("Location: ../view/profile.php?msg=pw_failed");
exit();
?>
