<?php
// 1. Security & Configuration...
include "../settings/core.php";
include "../settings/connection.php";

// Enforce login...
check_login(); 

// 2. Fetch Inventory Data with search/filter/sort
$where = [];
if (!empty($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $where[] = "(item_name LIKE '%$q%' OR serial_number LIKE '%$q%')";
}
if (!empty($_GET['category'])) {
    $cat = intval($_GET['category']);
    $where[] = "category_id = $cat";
}
if (!empty($_GET['status'])) {
    $st = mysqli_real_escape_string($conn, $_GET['status']);
    $where[] = "status = '$st'";
}
$sql = "SELECT * FROM inventory" . (count($where) ? " WHERE " . implode(" AND ", $where) : "");
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
if ($sort === 'name') $sql .= " ORDER BY item_name";
if ($sort === 'status') $sql .= " ORDER BY status";
if ($sort === 'category') $sql .= " ORDER BY category_id";
$result = $conn->query($sql);
?>

<?php include 'header.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background-color: #800000;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Mecha-Lab Student</a>
        <div class="d-flex text-white align-items-center">
            <a href="profile.php" class="btn btn-outline-light btn-sm me-3">Profile</a>
            <a href="my_requests.php" class="btn btn-outline-light btn-sm me-3">My Requests</a>
            <span class="me-3">Welcome, <?php echo $_SESSION['fname']; ?></span>
            <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <form class="row g-2 mb-3" method="GET" action="dashboard.php">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Search name or serial" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';?>">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="1" <?php if(isset($_GET['category']) && $_GET['category']=='1') echo 'selected'; ?>>Microcontroller</option>
                <option value="2" <?php if(isset($_GET['category']) && $_GET['category']=='2') echo 'selected'; ?>>Sensor</option>
                <option value="3" <?php if(isset($_GET['category']) && $_GET['category']=='3') echo 'selected'; ?>>Motor</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="Available" <?php if(isset($_GET['status']) && $_GET['status']=='Available') echo 'selected'; ?>>Available</option>
                <option value="Borrowed" <?php if(isset($_GET['status']) && $_GET['status']=='Borrowed') echo 'selected'; ?>>Borrowed</option>
                <option value="Broken" <?php if(isset($_GET['status']) && $_GET['status']=='Broken') echo 'selected'; ?>>Broken</option>
                <option value="Lost" <?php if(isset($_GET['status']) && $_GET['status']=='Lost') echo 'selected'; ?>>Lost</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="">Sort By</option>
                <option value="name" <?php if(isset($_GET['sort']) && $_GET['sort']=='name') echo 'selected'; ?>>Name</option>
                <option value="status" <?php if(isset($_GET['sort']) && $_GET['sort']=='status') echo 'selected'; ?>>Status</option>
                <option value="category" <?php if(isset($_GET['sort']) && $_GET['sort']=='category') echo 'selected'; ?>>Category</option>
            </select>
        </div>
        <div class="col-md-1 d-grid">
            <button class="btn btn-primary-custom" type="submit">Filter</button>
        </div>
    </form>
    <?php 
        if (isset($_GET['msg'])) {
            $map = [
                'item_unavailable' => ['class' => 'warning', 'text' => 'Item is currently unavailable'],
                'item_not_found' => ['class' => 'danger', 'text' => 'Item not found'],
                'already_requested' => ['class' => 'info', 'text' => 'You already have a request for this item'],
                'request_failed' => ['class' => 'danger', 'text' => 'Failed to create request'],
                'borrow_limit' => ['class' => 'warning', 'text' => 'Borrow limit reached'],
                'category_limit' => ['class' => 'warning', 'text' => 'Category limit reached']
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
                                if ($row['status'] == 'Available' || $row['status'] == 'Borrowed') {
                                    echo "<td>
                                            <form action='../actions/request_loan.php' method='POST' class='d-inline'>
                                                <input type='hidden' name='item_id' value='" . $row['item_id'] . "'>
                                                <div class='input-group input-group-sm'>
                                                    <input type='date' name='desired_start_date' class='form-control' placeholder='Start'>
                                                    <input type='date' name='desired_end_date' class='form-control' placeholder='End'>
                                                    <button type='submit' name='request_btn' class='btn btn-sm btn-primary-custom'>" . ($row['status']=='Available' ? 'Request Borrow' : 'Join Waitlist') . "</button>
                                                </div>
                                            </form>
                                          </td>";
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
