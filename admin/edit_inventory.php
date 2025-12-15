<?php
include "../settings/core.php";
include "../settings/connection.php";
check_admin_role(); 

// Gets the Item ID from URL...
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM inventory WHERE item_id = '$id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Item not found.");
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<?php include '../view/header.php'; ?>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header text-white" style="background-color: #800000;">
            <h4 class="mb-0">Edit Component</h4>
        </div>
        <div class="card-body">
            <form action="../actions/update_item.php" method="POST">
                <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="item_name" class="form-control" value="<?php echo $row['item_name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Serial Number</label>
                    <input type="text" name="serial_number" class="form-control" value="<?php echo $row['serial_number']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="1" <?php if($row['category_id'] == 1) echo 'selected'; ?>>Microcontroller</option>
                        <option value="2" <?php if($row['category_id'] == 2) echo 'selected'; ?>>Sensor</option>
                        <option value="3" <?php if($row['category_id'] == 3) echo 'selected'; ?>>Motor</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Status</label>
                    <select name="status" class="form-select">
                        <option value="Available" <?php if($row['status'] == 'Available') echo 'selected'; ?>>Available</option>
                        <option value="Borrowed" <?php if($row['status'] == 'Borrowed') echo 'selected'; ?>>Borrowed</option>
                        <option value="Broken" <?php if($row['status'] == 'Broken') echo 'selected'; ?>>Broken</option>
                        <option value="Lost" <?php if($row['status'] == 'Lost') echo 'selected'; ?>>Lost</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="update_btn" class="btn btn-primary-custom">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>