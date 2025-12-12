// /assets/js/validation.js

/**
 * Validates the Serial Number input against a specific Regex pattern.
 * Format required: MECH-YYYY-XX (e.g., MECH-2025-A1)
 * @returns {boolean} - True if valid, False if invalid (stops form submission).
 */
function validateSerial() {
    // 1. Get the input element and error message element
    const serialInput = document.getElementById('serialInput');
    const errorDiv = document.getElementById('serialError');
    
    // 2. Define the Regular Expression
    // ^      : Start of string
    // MECH-  : Must start with exact characters "MECH-"
    // \d{4}  : Exactly 4 digits (representing year)
    // -      : A hyphen separator
    // [A-Z]{2}: Exactly 2 uppercase letters
    // $      : End of string
    const regexPattern = /^MECH-\d{4}-[A-Z]{2}$/;

    // 3. Test the input value against the pattern
    if (!regexPattern.test(serialInput.value)) {
        // Validation FAILED
        // Show the error message
        errorDiv.style.display = 'block';
        // Add Bootstrap invalid class for visual cue
        serialInput.classList.add('is-invalid');
        // Prevent form submission
        return false;
    } else {
        // Validation PASSED
        // Hide error message if it was shown
        errorDiv.style.display = 'none';
        serialInput.classList.remove('is-invalid');
        // Allow form submission to proceed to PHP
        return true;
    }
}