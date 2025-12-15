<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();

if (!isset($_GET['id'])) {
    header("Location: my_requests.php");
    exit();
}
$loanId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

$sql = "SELECT l.*, i.item_name, i.serial_number, u.fname, u.lname 
        FROM loans l 
        JOIN inventory i ON i.item_id = l.item_id
        JOIN users u ON u.user_id = l.user_id
        WHERE l.loan_id = $loanId AND l.user_id = $userId";
$res = $conn->query($sql);
if (!$res || $res->num_rows !== 1) {
    header("Location: my_requests.php");
    exit();
}
$row = $res->fetch_assoc();
?>

<?php include 'header.php'; ?>

<div class="container mt-4" style="max-width: 700px;">
    <div class="card shadow">
        <div class="card-header" style="background-color:#800000;" >
            <h5 class="text-white mb-0">Loan Receipt</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <p class="mb-1"><strong>Student:</strong> <?php echo $row['fname'] . ' ' . $row['lname']; ?></p>
                    <p class="mb-1"><strong>Item:</strong> <?php echo $row['item_name']; ?></p>
                    <p class="mb-1"><strong>Serial:</strong> <?php echo $row['serial_number']; ?></p>
                </div>
                <div class="col-6">
                    <p class="mb-1"><strong>Status:</strong> <?php echo $row['status']; ?></p>
                    <p class="mb-1"><strong>Requested:</strong> <?php echo date('Y-m-d H:i', strtotime($row['request_date'])); ?></p>
                    <p class="mb-1"><strong>Due Date:</strong> <?php echo $row['due_date'] ? date('Y-m-d', strtotime($row['due_date'])) : '-'; ?></p>
                </div>
            </div>
            <hr>
            <p class="mb-0"><strong>Notes:</strong> <?php echo $row['notes'] ? htmlspecialchars($row['notes']) : 'None'; ?></p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="javascript:window.print()" class="btn btn-outline-secondary">Print</a>
            <a href="my_requests.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
 </div>

<?php include 'footer.php'; ?>

