<?php include 'view/header.php'; ?>

<div class="container d-flex justify-content-center">
    <div class="login-container" style="max-width: 500px;">
        <div class="text-center mb-4">
            <h3 class="text-dark fw-bold">Create Account</h3>
            <p class="text-muted">Join the Mecha-Lab System</p>
        </div>

        <form action="actions/register_user.php" method="POST" id="registerForm">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="fname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lname" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" id="regEmail" placeholder="student@ashesi.edu.gh" required>
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">I am a...</label>
                <select class="form-select" name="role">
                    <option value="Student">Student</option>
                    <option value="Admin">Lab Administrator</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="regPassword" required>
                <small class="text-muted" style="font-size: 0.8rem;">Must be at least 6 characters.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="cpassword" id="regCPassword" required>
                <small id="passwordMatchError" class="text-danger" style="display:none;">Passwords do not match.</small>
            </div>

            <button type="submit" name="register_btn" class="btn btn-primary-custom w-100 py-2">Sign Up</button>
            
            <div class="mt-3 text-center">
                <small>Already have an account? <a href="index.php" class="text-decoration-none" style="color: var(--ashesi-maroon);">Login Here</a></small>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        var pass = document.getElementById('regPassword').value;
        var cpass = document.getElementById('regCPassword').value;
        
        if (pass !== cpass) {
            e.preventDefault(); // Stops form submission...
            document.getElementById('passwordMatchError').style.display = 'block';
        }
        
        if (pass.length < 6) {
            e.preventDefault();
            alert("Password must be at least 6 characters long.");
        }
    });
</script>

<?php include 'view/footer.php'; ?>