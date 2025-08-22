<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Syntax On Air</title>
    <link rel="stylesheet" href="signup.css">
    <script src="signu.js" defer></script>
</head>
<body>
    <?php require_once("../header/header.php"); ?>

    <main>
        <section class="auth-form">
            <h2>Sign Up</h2>
            <form id="signup-form" action="signLogging.php" method="POST">
                <div class="card-container">
                    <div class="card" id="passport-card">
                        <h3>Passport Information</h3>
                        <div class="form-group">
                            <label for="passport_number">Passport Number:</label>
                            <input type="text" id="passport_number" name="passport_number" required>
                            <span id="passportError" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="nationality">Nationality:</label>
                            <select id="nationality" name="nationality" required>
                                <option value="">Select Nationality</option>
                                <option value="American">American</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="ESwatini">ESwatini</option>
                                <option value="South African">South African</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth:</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required>
                            <span id="dobError" class="error-message"></span>
                        </div>
                    </div>
                    <div class="card" id="personal-card">
                        <h3>Personal Information</h3>
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number:</label>
                            <input type="tel" id="phone_number" name="phone_number" placeholder="Optional">
                        </div>
                    </div>
                    <div class="card" id="account-card">
                        <h3>Account Information</h3>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select id="role" name="role" required>
                                <option value="Passenger">Passenger</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>
                            <span id="userEr" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>
                            <span id="pwEr" class="error-message"></span>
                            <span id="pwStrengthFeedback"></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password:</label>
                            <input type="password" id="confirm-password" name="confirm-password" required>
                            <span id="pwErC" class="error-message"></span>
                            <span id="pwMatchFeedback"></span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
            <p>Already have an account? <a href="signin.php">Sign In</a></p>
        </section>
    </main>

    <?php require_once("../footer/footer.php"); ?>
</body>
</html>
