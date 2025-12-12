<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Register for Mecha-Lab</h4>
                </div>
                <div class="card-body">
                    <form action="actions/register_user.php" method="POST">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="fname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                             <label>Confirm Password</label>
                            <input type="password" name="cpassword" class="form-control" required>
                        </div>
                        <button type="submit" name="register_btn" class="btn btn-primary w-100">Sign Up</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>Already have an account? <a href="login.php">Login here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>