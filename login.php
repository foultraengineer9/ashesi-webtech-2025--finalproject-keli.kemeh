<?php include 'includes/header.php'; ?>

<div class="container mt-5" style="height: 60vh;">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h3><i class="fas fa-sign-in-alt"></i> Login</h3>
                </div>
                <div class="card-body p-4">
                    <form action="actions/login_user.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="student@ashesi.edu.gh" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="********" required>
                        </div>
                        <button type="submit" name="login_btn" class="btn btn-success w-100 py-2">Login</button>
                    </form>
                </div>
                <div class="card-footer text-center bg-white border-0">
                    <small>Don't have an account? <a href="register.php" class="text-success fw-bold">Register</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>