<?php
// 1. Security & Configuration...
include "../settings/core.php";
include "../settings/connection.php";

// Enforce login...
check_login(); 

// 2. Fetch Inventory Data...
// all items selected to display in the table...
$sql = "SELECT * FROM inventory";
$result = $conn->query($sql);
?>

<?php include 'header.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-maroon mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Mecha-Lab Student</a>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Welcome, <?php echo $_SESSION['fname']; ?></span>
            <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h2 class="text-dark fw-bold">Lab Inventory Status</h2>
            <p class="text-muted">Check availability before visiting the lab.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th>Serial #</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Looping through each item in the database...
                            while($row = $result->fetch_assoc()) {
                                
                                // Logic to choose badge color based on status...
                                $status_color = 'bg-secondary'; // default
                                if ($row['status'] == 'Available') $status_color = 'bg-success';
                                if ($row['status'] == 'Borrowed') $status_color = 'bg-warning text-dark';
                                if ($row['status'] == 'Broken') $status_color = 'bg-danger';

                                echo "<tr>";
                                echo "<td class='fw-bold'>" . $row['item_name'] . "</td>";
                                echo "<td>" . $row['serial_number'] . "</td>";
                                echo "<td><span class='badge rounded-pill $status_color'>" . $row['status'] . "</span></td>";
                                
                                // Action Column...
                                if ($row['status'] == 'Available') {
                                    echo "<td><button class='btn btn-sm btn-primary-custom' disabled>Visit Lab to Borrow</button></td>";
                                } else {
                                    echo "<td><span class='text-muted small'>Unavailable</span></td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            // If table is empty...
                            echo "<tr><td colspan='4' class='text-center text-muted py-4'>No inventory items found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>