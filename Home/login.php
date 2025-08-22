<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Login</title>
</head>
<body>
<?php
    session_start();
    session_regenerate_id(true);
    require_once("../Home/config.php");

    // Get the submitted username and password from the form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Create a database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare a statement to select user data including the role
    $stmt = $conn->prepare("SELECT username, hashed_password, role FROM passengers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['hashed_password'])) {
            // Check if the user role is Passenger
            if ($user['role'] === 'Passenger') {
       
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Store role in session
                
    
                header("Location: ../Dashboard/dashboard.php");
                exit();
            } else {
  
                echo "<p>You do not have permission to access the passenger dashboard.</p>";
            }
        } else {
            // If the password is incorrect
            echo "<p>Invalid password. Please try again.</p>";
        }
    } else {
        // If the username does not exist
        echo "<p>No user found with that username. Please try again.</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
?>
</body>
</html>
