<?php include 'view/header.php'; ?>

<div class="container d-flex justify-content-center">
    <div class="login-container">
        <div class="text-center mb-4">
            <h3 class="text-dark fw-bold">Mecha-Lab Manager</h3>
            <p class="text-muted">Sign in to borrow equipment</p>
        </div>

        <form action="actions/login_user.php" method="POST" id="loginForm">
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="student@ashesi.edu.gh" required>
                <small id="emailError" class="text-danger" style="display:none;">Invalid email format.</small>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small id="passwordError" class="text-danger" style="display:none;">Password is required.</small>
            </div>

            <button type="submit" name="login_btn" class="btn btn-primary-custom w-100 py-2">Sign In</button>
            
            <div class="mt-3 text-center">
                <small>Don't have an account? <a href="register.php" class="text-decoration-none" style="color: var(--ashesi-maroon);">Register Here</a></small>
            </div>
        </form>
    </div>
</div>

<?php include 'view/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/validation.js"></script> 

</body>
</html>