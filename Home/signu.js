document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signup-form');
    const cards = document.querySelectorAll('.card');
    const inputs = document.querySelectorAll('input');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm-password');
    const dateOfBirth = document.getElementById('date_of_birth');
    const passportNumber = document.getElementById('passport_number');

    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            cards.forEach(card => card.classList.remove('active'));
            this.closest('.card').classList.add('active');
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm()) {
            form.submit();
        }
    });

    password.addEventListener('input', function() {
        const strength = checkPasswordStrength(this.value);
        updatePasswordStrengthFeedback(strength);
    });

    confirmPassword.addEventListener('input', function() {
        updatePasswordMatchFeedback();
    });

    dateOfBirth.addEventListener('change', function() {
        validateDateOfBirth();
    });

    passportNumber.addEventListener('input', function() {
        validatePassportNumber();
    });

    function validateForm() {
        let isValid = true;

        // Validate all required fields
        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value) {
                showError(input, 'This field is required');
                isValid = false;
            }
        });

        // Validate email
        const email = document.getElementById('email');
        if (email.value && !isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        // Validate password
        if (password.value && checkPasswordStrength(password.value) === 'weak') {
            showError(password, 'Password is too weak');
            isValid = false;
        }

        // Validate password match
        if (password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }

        // Validate date of birth
        if (!validateDateOfBirth()) {
            isValid = false;
        }

        // Validate passport number
        if (!validatePassportNumber()) {
            isValid = false;
        }

        return isValid;
    }

    function showError(input, message) {
        const errorSpan = input.nextElementSibling;
        errorSpan.textContent = message;
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function checkPasswordStrength(password) {
        const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        const mediumRegex = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{6,}$/;

        if (strongRegex.test(password)) return 'strong';
        if (mediumRegex.test(password)) return 'medium';
        return 'weak';
    }

    function updatePasswordStrengthFeedback(strength) {
        const feedback = document.getElementById('pwStrengthFeedback');
        switch (strength) {
            case 'strong':
                feedback.textContent = 'Strong password';
                feedback.style.color = 'green';
                break;
            case 'medium':
                feedback.textContent = 'Medium strength password';
                feedback.style.color = 'orange';
                break;
            case 'weak':
                feedback.textContent = 'Weak password';
                feedback.style.color = 'red';
                break;
        }
    }

    function updatePasswordMatchFeedback() {
        const feedback = document.getElementById('pwMatchFeedback');
        if (password.value === confirmPassword.value) {
            feedback.textContent = 'Passwords match';
            feedback.style.color = 'green';
        } else {
            feedback.textContent = 'Passwords do not match';
            feedback.style.color = 'red';
        }
    }

    function validateDateOfBirth() {
        const dob = new Date(dateOfBirth.value);
        const today = new Date();
        const errorSpan = document.getElementById('dobError');

        if (dob > today) {
            errorSpan.textContent = 'Date of birth cannot be in the future';
            return false;
        } else {
            errorSpan.textContent = '';
            return true;
        }
    }

    function validatePassportNumber() {
        const passportRegex = /^[A-Z]\d+$|^\d+$/;
        const errorSpan = document.getElementById('passportError');

        if (!passportRegex.test(passportNumber.value)) {
            errorSpan.textContent = 'Invalid passport number format';
            return false;
        } else {
            errorSpan.textContent = '';
            return true;
        }
    }
});