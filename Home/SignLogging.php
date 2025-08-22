<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        .error { color: red; }
        .success { color: green; }
        /* Add more styles as needed */
    </style>
</head>
<body>
    <?php
        session_start();

        // Retrieve user input securely
        $f_name = trim(htmlspecialchars($_POST['first_name'] ?? ''));
        $l_name = trim(htmlspecialchars($_POST['last_name'] ?? ''));
        $email = trim(htmlspecialchars($_POST['email'] ?? ''));
        $phone_number = trim(htmlspecialchars($_POST['phone_number'] ?? ''));
        $passport = trim(htmlspecialchars($_POST['passport_number'] ?? ''));
        $nationality = trim(htmlspecialchars($_POST['nationality'] ?? ''));
        $DOB = trim(htmlspecialchars($_POST['date_of_birth'] ?? ''));
        $username = trim(htmlspecialchars($_POST['username'] ?? ''));
        $password = $_POST['password'] ?? '';
        $conPass = $_POST['confirm-password'] ?? '';
        $role = $_REQUEST['role'];

        // Basic server-side validation
        $errors = [];

        if (empty($f_name) || empty($l_name) || empty($email) || empty($passport) || empty($nationality) || empty($DOB) || empty($username) || empty($password) || empty($conPass)) {
            $errors[] = "All required fields must be filled out.";
        }

        // Password matching check
        if ($password !== $conPass) {
            $errors[] = "Passwords do not match. Please try again.";
        }

        // Optional: Add more validation (e.g., email format, password strength, etc.)

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
            }
            echo "<p><a href='signUp.php'>Go back to Sign Up</a></p>";
            exit();
        }

        // Include database configuration
        require_once("config.php");

        // Create a database connection using MySQLi with error handling
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($conn->connect_error) {
            die("<p class='error'>Connection to the database failed: " . htmlspecialchars($conn->connect_error) . "</p>");
        }

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare statement to check if username or email already exists
        $stmt = $conn->prepare("SELECT * FROM passengers WHERE email = ? OR username = ?");
        if (!$stmt) {
            die("<p class='error'>Prepare failed: " . htmlspecialchars($conn->error) . "</p>");
        }
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user already exists
        if ($result->num_rows > 0) {
            echo "<p class='error'>User with this email or username already exists. Please try a different email or username.</p>";
            echo "<p><a href='signUp.php'>Go back to Sign Up</a></p>";
            $stmt->close();
            $conn->close();
            exit();
        }

        // Close the previous statement
        $stmt->close();

        // Prepare an SQL statement to insert new user data, including the hashed password
        // Set default role to 'Passenger' or 'Admin' based on requirement
        // Here we are setting it to 'Passenger'
        $stmt = $conn->prepare("INSERT INTO passengers (first_name, last_name, email, phone_number, passport_number, nationality, date_of_birth, username, hashed_password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("<p class='error'>Prepare failed: " . htmlspecialchars($conn->error) . "</p>");
        }
        $stmt->bind_param("ssssssssss", $f_name, $l_name, $email, $phone_number, $passport, $nationality, $DOB, $username, $hashed_password, $role);

        // Execute the statement
        if ($stmt->execute()) {
            // Success: Redirect to a success page or dashboard
            // Set session variables if you wish to log the user in immediately
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role; // Set the role in the session

            // Redirect without echoing to prevent headers already sent error
            // header("Location: ../Dashboard/dashboard.php");
            // Redirect based on the user's role
            if ($role === 'Admin') {
                header("Location: ../admin/admin.php"); // Redirect to admin dashboard
            } else {
                header("Location: ../Dashboard/dashboard.php"); // Redirect to passenger dashboard
            }
            exit();
        } else {
            echo "<p class='error'>Error registering user: " . htmlspecialchars($stmt->error) . "</p>";
            echo "<p><a href='signUp.php'>Go back to Sign Up</a></p>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    ?>
</body>
</html>
