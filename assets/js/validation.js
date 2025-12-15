document.addEventListener("DOMContentLoaded", function() {
    // For selecting the form and inputs...
    const loginForm = document.getElementById("loginForm");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    
    // For selecting the error message elements...
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");

    
    //  *Regex reuirement...
    // This pattern checks for standard email format: text@text.domain

    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // Listen for the 'submit' event...
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            let isValid = true;
            
            // 1. Reset previous error messages...
            emailError.style.display = "none";
            passwordError.style.display = "none";
            emailInput.classList.remove("is-invalid");
            passwordInput.classList.remove("is-invalid");

            // 2. Validate Email using Regex...
            if (!emailPattern.test(emailInput.value)) {
                event.preventDefault(); // Stop the form!
                emailError.style.display = "block";
                emailError.innerText = "Please enter a valid Ashesi email address.";
                emailInput.classList.add("is-invalid"); // Bootstrap error styling
                isValid = false;
            }

            // 3. Validate Password (must not be empty)
            if (passwordInput.value.trim() === "") {
                event.preventDefault(); // Stop the form!
                passwordError.style.display = "block";
                passwordInput.classList.add("is-invalid");
                isValid = false;
            }

            // If isValid remains true, the form will submit to 'actions/login_user.php'...:)
        });
    }
});