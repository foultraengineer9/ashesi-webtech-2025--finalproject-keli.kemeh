<?php
// 1. Security & Configuration...
include "../settings/core.php";
include "../settings/connection.php";

// Check for Login AND Admin Role...

check_admin_role(); 

// 2. Fetch All Inventory...
$sql = "SELECT * FROM Inventory";
$result = $conn->query($sql);
?>

<?php include '../view/header.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">MECHA-LAB | ADMIN</a>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Admin: <?php echo $_SESSION['fname']; ?></span>
            <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operation completed successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Inventory Management</h2>
        
        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addItemModal">
            + Add New Component
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Serial #</th>
                        <th>Category</th> <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // Status Color Logic..
                            $status_color = 'bg-secondary';
                            if ($row['status'] == 'Available') $status_color = 'bg-success';
                            if ($row['status'] == 'Borrowed') $status_color = 'bg-warning text-dark';
                            if ($row['status'] == 'Broken') $status_color = 'bg-danger';

                            echo "<tr>";
                            echo "<td class='fw-bold'>" . $row['item_name'] . "</td>";
                            echo "<td>" . $row['serial_number'] . "</td>";
                            echo "<td>" . ($row['category_id'] == 1 ? 'Microcontroller' : 'Sensor') . "</td>"; // Simple logic for now
                            echo "<td><span class='badge rounded-pill $status_color'>" . $row['status'] . "</span></td>";
                            
                            // THE update (U) and delete (D) buttons...
                            echo "<td>
                                    <a href='edit_inventory.php?id=" . $row['item_id'] . "' class='btn btn-sm btn-outline-primary me-2'>Edit</a>
                                    <a href='../actions/delete_item.php?id=" . $row['item_id'] . "' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-muted'>No items found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../actions/add_item.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Component Name</label>
                        <input type="text" name="item_name" class="form-control" required placeholder="e.g. Raspberry Pi 4">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control" required placeholder="e.g. RPI-2025-X">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="1">Microcontroller</option>
                            <option value="2">Sensor</option>
                            <option value="3">Motor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_item_btn" class="btn btn-primary-custom">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>