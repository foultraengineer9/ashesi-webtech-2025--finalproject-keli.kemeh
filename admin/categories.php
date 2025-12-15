<?php
include "../settings/core.php";
include "../settings/connection.php";

check_admin_role();

$cats = $conn->query("SELECT * FROM categories ORDER BY category_id");
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
        <h2 class="fw-bold">Categories</h2>
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCatModal">+ Add Category</button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cats && $cats->num_rows > 0): ?>
                        <?php while($row = $cats->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['category_id']; ?></td>
                                <td class="fw-bold"><?php echo $row['category_name']; ?></td>
                                <td>
                                    <form action="../actions/update_category.php" method="POST" class="d-inline">
                                        <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="category_name" class="form-control" value="<?php echo htmlspecialchars($row['category_name']); ?>" required>
                                            <button type="submit" name="update_category_btn" class="btn btn-outline-primary">Save</button>
                                        </div>
                                    </form>
                                    <a href="../actions/delete_category.php?id=<?php echo $row['category_id']; ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Delete this category?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center text-muted">No categories.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../actions/add_category.php" method="POST">
                <div class="modal-body">
                    <input type="text" name="category_name" class="form-control" placeholder="e.g. Microcontroller" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_category_btn" class="btn btn-primary-custom">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>

