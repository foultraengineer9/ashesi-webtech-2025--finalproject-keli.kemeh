<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit(); 
}

// 1. Include Database Connection
require_once 'settings/db_connect.php';
// ... rest of your index.php code follows ...
?>

<?php
// 1. Include Database Connection
require_once 'settings/db_connect.php';

// 2. READ Operation: Fetch all components for the table
$sql = "SELECT * FROM Components ORDER BY component_id DESC";
$result = $conn->query($sql);

// 3. ANALYTICS Operation: Get counts for the chart
// We run three quick queries to get the numbers for Available, Borrowed, and Broken.
$avail_count = $conn->query("SELECT COUNT(*) AS c FROM Components WHERE status='Available'")->fetch_assoc()['c'];
$borrow_count = $conn->query("SELECT COUNT(*) AS c FROM Components WHERE status='Borrowed'")->fetch_assoc()['c'];
$broken_count = $conn->query("SELECT COUNT(*) AS c FROM Components WHERE status='Broken'")->fetch_assoc()['c'];

// 4. Include the Header Frame
include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6"><i class="fas fa-microchip"></i> Lab Inventory Dashboard</h1>
        <p class="text-muted">Real-time tracking of Mechatronics hardware.</p>
    </div>
    <div class="col-md-4 text-end align-self-center">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus-circle"></i> Add New Component
        </button>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center font-weight-bold">
                Equipment Status Overview
            </div>
            <div class="card-body">
                <canvas id="analyticsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-secondary text-white">
        <i class="fas fa-list"></i> Current Inventory List
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Serial Number</th>
                    <th>Component Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><code class="text-primary fw-bold"><?php echo $row['serial_number']; ?></code></td>
                        <td><?php echo $row['component_name']; ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo $row['category']; ?></span></td>
                        <td>
                            <?php
                            // Dynamic badge color based on status
                            $statusClass = match($row['status']) {
                                'Available' => 'bg-success',
                                'Borrowed' => 'bg-warning text-dark',
                                'Broken', 'Lost' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span>
                        </td>
                        <td>
                             <a href="actions/inventory_actions.php?delete_id=<?php echo $row['component_id']; ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Are you permanently removing this item?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-3">No components found in database.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Register New Component</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actions/inventory_actions.php" method="POST" onsubmit="return validateSerial()">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Component Name</label>
                        <input type="text" name="c_name" class="form-control" placeholder="e.g., Arduino Uno R3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="c_category" class="form-select" required>
                            <option value="">Select Category...</option>
                            <option value="Microcontroller">Microcontroller</option>
                            <option value="Sensor">Sensor</option>
                            <option value="Motor">Motor</option>
                            <option value="Tool">Tool</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="c_serial" id="serialInput" class="form-control" placeholder="Format: MECH-YYYY-XX" required>
                        <div id="serialError" class="text-danger mt-1" style="display:none;">
                            <small><i class="fas fa-exclamation-circle"></i> Invalid Format! Must use <strong>MECH-####-XX</strong> (e.g., MECH-2025-A1)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_item_btn" class="btn btn-success">Save Component</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialize the Analytics Chart
    const ctx = document.getElementById('analyticsChart');
    new Chart(ctx, {
        type: 'doughnut', // A doughnut chart is great for status distribution
        data: {
            labels: ['Available', 'Borrowed', 'Broken/Lost'],
            datasets: [{
                // Inject PHP variables into JS data array
                data: [<?php echo $avail_count; ?>, <?php echo $borrow_count; ?>, <?php echo $broken_count; ?>],
                backgroundColor: [
                    '#198754', // Success Green
                    '#ffc107', // Warning Yellow
                    '#dc3545'  // Danger Red
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>