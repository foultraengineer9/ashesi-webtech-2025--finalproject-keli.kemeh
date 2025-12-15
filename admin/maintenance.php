<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

$tickets = $conn->query("SELECT m.*, i.item_name, i.serial_number, u.fname, u.lname 
                         FROM maintenance_tickets m
                         JOIN inventory i ON i.item_id = m.item_id
                         LEFT JOIN users u ON u.user_id = m.opened_by
                         ORDER BY m.opened_at DESC");
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Maintenance Tickets</h2>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Reporter</th>
                        <th>Status</th>
                        <th>Opened</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tickets && $tickets->num_rows > 0): ?>
                        <?php while($t = $tickets->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold"><?php echo $t['item_name']; ?> (<?php echo $t['serial_number']; ?>)</td>
                                <td><?php echo $t['fname'] ? $t['fname'] . ' ' . $t['lname'] : 'N/A'; ?></td>
                                <td><?php echo $t['status']; ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($t['opened_at'])); ?></td>
                                <td>
                                    <form action="../actions/update_ticket.php" method="POST" class="d-inline">
                                        <input type="hidden" name="ticket_id" value="<?php echo $t['ticket_id']; ?>">
                                        <select name="status" class="form-select form-select-sm d-inline w-auto">
                                            <option value="Open" <?php if($t['status']=='Open') echo 'selected'; ?>>Open</option>
                                            <option value="InProgress" <?php if($t['status']=='InProgress') echo 'selected'; ?>>In Progress</option>
                                            <option value="Closed" <?php if($t['status']=='Closed') echo 'selected'; ?>>Closed</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary" name="update_ticket_btn" type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-muted">Details: <?php echo htmlspecialchars($t['details']); ?>
                                    <?php if ($t['photo_url']): ?>
                                        <br><img src="../<?php echo $t['photo_url']; ?>" alt="Photo" style="max-height:120px;"/>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">No tickets.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>

