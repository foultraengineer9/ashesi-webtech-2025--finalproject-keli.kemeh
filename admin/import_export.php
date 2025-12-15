<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();
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

<div class="container" style="max-width:700px;">
    <div class="card mb-4">
        <div class="card-header">Export Inventory</div>
        <div class="card-body">
            <a href="../actions/export_inventory.php" class="btn btn-primary-custom">Download CSV</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Import Inventory</div>
        <div class="card-body">
            <form action="../actions/import_inventory.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="file" name="csv" class="form-control" accept=".csv" required>
                </div>
                <button type="submit" name="import_btn" class="btn btn-primary-custom">Import CSV</button>
            </form>
            <small class="text-muted">Columns: item_name, serial_number, category_id, description, status</small>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>

