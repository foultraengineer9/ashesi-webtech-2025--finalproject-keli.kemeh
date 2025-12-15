<?php
include "../settings/core.php";
include "../settings/connection.php";

check_login();
$userId = $_SESSION['user_id'];

$sql = "SELECT l.*, i.item_name, i.serial_number FROM loans l 
        JOIN inventory i ON i.item_id = l.item_id
        WHERE l.user_id = $userId ORDER BY l.request_date DESC";
$result = $conn->query($sql);
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

<div class="container">
    <?php 
        if (isset($_GET['msg'])) {
            $map = [
                'request_created' => ['class' => 'success', 'text' => 'Borrow request submitted'],
                'request_cancelled' => ['class' => 'warning', 'text' => 'Request cancelled'],
                'cancel_failed' => ['class' => 'danger', 'text' => 'Failed to cancel request']
            ];
            $key = $_GET['msg'];
            if (isset($map[$key])) {
                echo '<div class="alert alert-' . $map[$key]['class'] . ' alert-dismissible fade show mt-2" role="alert">' .
                     $map[$key]['text'] .
                     '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    ?>
    <div class="row mb-3">
        <div class="col">
            <h2 class="text-dark fw-bold">My Borrow Requests</h2>
            <p class="text-muted">Track status and history.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Serial #</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <?php
                                    $badge = 'bg-secondary';
                                    if ($row['status'] == 'Pending') $badge = 'bg-info text-dark';
                                    if ($row['status'] == 'Approved') $badge = 'bg-primary';
                                    if ($row['status'] == 'Borrowed') $badge = 'bg-warning text-dark';
                                    if ($row['status'] == 'Returned') $badge = 'bg-success';
                                    if ($row['status'] == 'Rejected') $badge = 'bg-danger';
                                ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $row['item_name']; ?></td>
                                    <td><?php echo $row['serial_number']; ?></td>
                                    <td><span class="badge rounded-pill <?php echo $badge; ?>"><?php echo $row['status']; ?></span></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($row['request_date'])); ?></td>
                                    <td><?php echo $row['due_date'] ? date('Y-m-d', strtotime($row['due_date'])) : '-'; ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'Pending'): ?>
                                            <a href="../actions/cancel_loan.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this request?');">Cancel</a>
                                        <?php elseif ($row['status'] == 'Borrowed'): ?>
                                            <a href="../actions/request_renewal.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-primary">Request Renewal</a>
                                            <a href="../actions/report_issue.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-warning">Report Issue</a>
                                            <a href="receipt.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-secondary">Receipt</a>
                                        <?php elseif ($row['status'] == 'Returned'): ?>
                                            <a href="receipt.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-secondary">Receipt</a>
                                        <?php else: ?>
                                            <span class="text-muted small">No action</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No requests found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 </div>

<?php include 'footer.php'; ?>
