<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();

if (isset($_GET['id'])) {
    $loanId = intval($_GET['id']);
    // Render a simple issue report form
    ?>
    <?php include '../view/header.php'; ?>
    <div class="container mt-4" style="max-width:600px;">
        <div class="card">
            <div class="card-header" style="background-color:#800000;">
                <h5 class="text-white mb-0">Report Issue</h5>
            </div>
            <form action="report_issue.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <input type="hidden" name="loan_id" value="<?php echo $loanId; ?>">
                    <div class="mb-3">
                        <label class="form-label">Describe the issue</label>
                        <textarea name="details" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Optional photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="../view/my_requests.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="submit_issue" class="btn btn-primary-custom">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../view/footer.php'; ?>
    <?php
    exit();
}

if (isset($_POST['submit_issue'])) {
    $loanId = intval($_POST['loan_id']);
    $userId = $_SESSION['user_id'];
    $details = mysqli_real_escape_string($conn, $_POST['details']);

    // Validate ownership
    $res = $conn->query("SELECT item_id FROM loans WHERE loan_id = $loanId AND user_id = $userId");
    if (!$res || $res->num_rows !== 1) {
        header("Location: ../view/my_requests.php?msg=issue_failed");
        exit();
    }
    $itemId = intval($res->fetch_assoc()['item_id']);

    // Handle upload
    $photoUrl = null;
    if (!empty($_FILES['photo']['name'])) {
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $target = $dir . DIRECTORY_SEPARATOR . time() . '_' . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photoUrl = 'uploads/' . basename($target);
        }
    }

    // Create maintenance ticket and mark item as Broken
    $stmt = $conn->prepare("INSERT INTO maintenance_tickets (item_id, opened_by, title, details, photo_url) VALUES (?, ?, ?, ?, ?)");
    $title = 'Issue reported by student';
    $stmt->bind_param("iisss", $itemId, $userId, $title, $details, $photoUrl);
    $conn->begin_transaction();
    try {
        $stmt->execute();
        $conn->query("UPDATE inventory SET status = 'Broken' WHERE item_id = $itemId");
        $conn->commit();
        header("Location: ../view/my_requests.php?msg=issue_reported");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../view/my_requests.php?msg=issue_failed");
        exit();
    }
}

header("Location: ../view/my_requests.php");
exit();
?>
