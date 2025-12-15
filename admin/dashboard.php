<?php
// 1. Security & Configuration...
include "../settings/core.php";
include "../settings/connection.php";

// Check for Login AND Admin Role...

check_admin_role(); 




// 1. Getting Total Items...
$total_query = "SELECT COUNT(*) as count FROM inventory";
$total_result = $conn->query($total_query);
$total_items = $total_result->fetch_assoc()['count'];

// 2. Geting Borrowed Items...
$borrowed_query = "SELECT COUNT(*) as count FROM inventory WHERE status = 'Borrowed'";
$borrowed_result = $conn->query($borrowed_query);
$borrowed_items = $borrowed_result->fetch_assoc()['count'];

// 3. Getting Broken/Lost Items...
$broken_query = "SELECT COUNT(*) as count FROM inventory WHERE status = 'Broken' OR status = 'Lost'";
$broken_result = $conn->query($broken_query);
$broken_items = $broken_result->fetch_assoc()['count'];

// 4. Get Available Items (Calculated)...
$available_items = $total_items - $borrowed_items - $broken_items;

// Analytics Logic Ends here...

// 2. Fetch All Inventory (Your existing code continues here...)
$sql = "SELECT * FROM inventory";

// 2. Fetch All Inventory...
$sql = "SELECT * FROM inventory";
$result = $conn->query($sql);
?>

<?php include '../view/header.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">MECHA-LAB | ADMIN</a>
        <div class="d-flex text-white align-items-center">
            <a href="requests.php" class="btn btn-outline-light btn-sm me-3">Manage Requests</a>
            <a href="categories.php" class="btn btn-outline-light btn-sm me-3">Categories</a>
            <a href="import_export.php" class="btn btn-outline-light btn-sm me-3">Import/Export</a>
            <a href="maintenance.php" class="btn btn-outline-light btn-sm me-3">Maintenance</a>
            <span class="me-3">Admin: <?php echo $_SESSION['fname']; ?></span>
            <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php 
        if (isset($_GET['msg'])) {
            $map = [
                'success' => ['class' => 'success', 'text' => 'Item added successfully'],
                'updated' => ['class' => 'info', 'text' => 'Item updated'],
                'deleted' => ['class' => 'danger', 'text' => 'Item deleted'],
                'cannot_delete_active' => ['class' => 'warning', 'text' => 'Cannot delete: item has active loans'],
                'approved' => ['class' => 'success', 'text' => 'Loan approved'],
                'rejected' => ['class' => 'warning', 'text' => 'Loan rejected'],
                'borrowed' => ['class' => 'primary', 'text' => 'Loan marked as borrowed'],
                'returned' => ['class' => 'success', 'text' => 'Loan marked as returned']
            ];
            $key = $_GET['msg'];
            if (isset($map[$key])) {
                echo '<div class="alert alert-' . $map[$key]['class'] . ' alert-dismissible fade show" role="alert">' .
                     $map[$key]['text'] .
                     '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    ?>

    <div class="row mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3 shadow-sm h-100">
                <div class="card-header">Total Inventory</div>
                <div class="card-body">
                    <h1 class="card-title fw-bold"><?php echo $total_items; ?></h1>
                    <p class="card-text">Items in database</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-dark bg-warning mb-3 shadow-sm h-100">
                <div class="card-header">On Loan</div>
                <div class="card-body">
                    <h1 class="card-title fw-bold"><?php echo $borrowed_items; ?></h1>
                    <p class="card-text">Students holding items</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3 shadow-sm h-100">
                <div class="card-header">Faulty / Lost</div>
                <div class="card-body">
                    <h1 class="card-title fw-bold"><?php echo $broken_items; ?></h1>
                    <p class="card-text">Needs replacement</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="inventoryChart" style="max-height: 150px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Setting uup the Pie Chart...
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    const inventoryChart = new Chart(ctx, {
        type: 'doughnut', 
        data: {
            labels: ['Available', 'Borrowed', 'Broken'],
            datasets: [{
                data: [
                    <?php echo $available_items; ?>, 
                    <?php echo $borrowed_items; ?>, 
                    <?php echo $broken_items; ?>
                ],
                backgroundColor: [
                    '#198754', // Green is for Available...
                    '#ffc107', // Yellow for Borrowed...
                    '#dc3545'  // and Red for Broken...
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false // Hides legend to save some space...
                }
            }
        }
    });
</script>

<?php include '../view/footer.php'; ?>

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

 
