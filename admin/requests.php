<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

// Fetch pending requests
$pending = $conn->query("SELECT l.*, u.fname, u.lname, i.item_name, i.serial_number
                         FROM loans l
                         JOIN users u ON u.user_id = l.user_id
                         JOIN inventory i ON i.item_id = l.item_id
                         WHERE l.status = 'Pending' ORDER BY l.request_date ASC");

// Fetch approved/borrowed for actioning and renewals
$active = $conn->query("SELECT l.*, u.fname, u.lname, i.item_name, i.serial_number
                        FROM loans l
                        JOIN users u ON u.user_id = l.user_id
                        JOIN inventory i ON i.item_id = l.item_id
                        WHERE l.status IN ('Approved','Borrowed') ORDER BY l.request_date DESC");
?>

<?php include '../view/header.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">MECHA-LAB | ADMIN</a>
        <div class="d-flex text-white align-items-center">
            <a href="dashboard.php" class="btn btn-outline-light btn-sm me-3">Inventory</a>
            <span class="me-3">Admin: <?php echo $_SESSION['fname']; ?></span>
            <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Borrow Requests</h2>
            <p class="text-muted">Approve, reject, and track active loans.</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">Pending Requests</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Item</th>
                            <th>Serial #</th>
                            <th>Requested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pending && $pending->num_rows > 0): ?>
                            <?php while($row = $pending->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                                    <td><?php echo $row['item_name']; ?></td>
                                    <td><?php echo $row['serial_number']; ?></td>
                                    <td>
                                        <?php echo date('Y-m-d H:i', strtotime($row['request_date'])); ?>
                                        <?php if ($row['desired_start_date'] || $row['desired_end_date']): ?>
                                            <br><span class="text-muted small">Desired: <?php echo $row['desired_start_date'] ? date('Y-m-d', strtotime($row['desired_start_date'])) : '-'; ?> → <?php echo $row['desired_end_date'] ? date('Y-m-d', strtotime($row['desired_end_date'])) : '-'; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="../actions/approve_loan.php" method="POST" class="d-inline">
                                            <input type="hidden" name="loan_id" value="<?php echo $row['loan_id']; ?>">
                                            <div class="input-group input-group-sm">
                                                <input type="date" name="due_date" class="form-control" required>
                                                <button type="submit" name="approve_btn" class="btn btn-success">Approve</button>
                                            </div>
                                        </form>
                                        <a href="../actions/reject_loan.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Reject this request?');">Reject</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted">No pending requests.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">Active Loans</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($active && $active->num_rows > 0): ?>
                            <?php while($row = $active->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                                    <td><?php echo $row['item_name']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['due_date'] ? date('Y-m-d', strtotime($row['due_date'])) : '-'; ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'Approved'): ?>
                                            <a href="../actions/mark_borrowed.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-primary">Mark Borrowed</a>
                                        <?php elseif ($row['status'] == 'Borrowed'): ?>
                                            <a href="../actions/mark_returned.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-success">Mark Returned</a>
                                            <?php if (intval($row['renewal_requested']) === 1): ?>
                                                <form action="../actions/approve_renewal.php" method="POST" class="d-inline ms-2">
                                                    <input type="hidden" name="loan_id" value="<?php echo $row['loan_id']; ?>">
                                                    <div class="input-group input-group-sm">
                                                        <input type="date" name="new_due_date" class="form-control" required>
                                                        <button type="submit" name="approve_renewal_btn" class="btn btn-outline-primary">Approve Renewal</button>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted small">No action</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted">No active loans.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>
