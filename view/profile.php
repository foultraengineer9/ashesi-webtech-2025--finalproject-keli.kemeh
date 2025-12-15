<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();
$userId = $_SESSION['user_id'];
$res = $conn->query("SELECT fname, lname, email FROM users WHERE user_id = $userId");
$user = $res ? $res->fetch_assoc() : null;
?>

<?php include 'header.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background-color: #800000;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">Mecha-Lab Student</a>
        <div class="d-flex text-white align-items-center">
            <a href="dashboard.php" class="btn btn-outline-light btn-sm me-3">Inventory</a>
            <span class="me-3">Welcome, <?php echo $_SESSION['fname']; ?></span>
            <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
    </nav>

<div class="container" style="max-width:700px;">
    <?php 
        if (isset($_GET['msg'])) {
            $map = [
                'profile_updated' => ['class' => 'success', 'text' => 'Profile updated'],
                'profile_failed' => ['class' => 'danger', 'text' => 'Failed to update profile'],
                'pw_mismatch' => ['class' => 'warning', 'text' => 'New passwords do not match'],
                'pw_incorrect' => ['class' => 'danger', 'text' => 'Current password incorrect'],
                'pw_changed' => ['class' => 'success', 'text' => 'Password changed'],
                'pw_failed' => ['class' => 'danger', 'text' => 'Failed to change password']
            ];
            $key = $_GET['msg'];
            if (isset($map[$key])) {
                echo '<div class="alert alert-' . $map[$key]['class'] . ' alert-dismissible fade show mt-2" role="alert">' .
                     $map[$key]['text'] .
                     '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    ?>
    <div class="card mb-4">
        <div class="card-header">Profile</div>
        <div class="card-body">
            <form action="../actions/update_profile.php" method="POST">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button class="btn btn-primary-custom" type="submit" name="update_profile_btn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Change Password</div>
        <div class="card-body">
            <form action="../actions/change_password.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary-custom" type="submit" name="change_password_btn">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
