<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();

if (isset($_POST['update_profile_btn'])) {
    $userId = $_SESSION['user_id'];
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $stmt = $conn->prepare("UPDATE users SET fname = ?, lname = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $fname, $lname, $userId);
    if ($stmt->execute()) {
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        header("Location: ../view/profile.php?msg=profile_updated");
        exit();
    }
}
header("Location: ../view/profile.php?msg=profile_failed");
exit();
?>
