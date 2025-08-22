<?php
session_start();
require_once("../Home/config.php");


$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';


$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a statement to select user data including the role
$stmt = $conn->prepare("SELECT username, hashed_password, role FROM passengers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();


$logFile = 'login_attempts.log';
$logHandle = fopen($logFile, 'a'); // 'a' mode to append logs

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['hashed_password'])) {
r
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Store role in session

        // Log the successful attempt
        $logEntry = date('Y-m-d H:i:s') . " | User: $username | Status: success\n";
        fwrite($logHandle, $logEntry);

        // Redirect to the passenger dashboard
        header("Location: ../Dashboard/dashboard.php");
        exit();
    } else {
  
        $logEntry = date('Y-m-d H:i:s') . " | User: $username | Status: failure (wrong password)\n";
        fwrite($logHandle, $logEntry);


        echo "<p>Invalid password. Please try again.</p>";
    }
} else {
    // Log the failed attempt (username not found)
    $logEntry = date('Y-m-d H:i:s') . " | User: $username | Status: failure (user not found)\n";
    fwrite($logHandle, $logEntry);

    // If the username does not exist
    echo "<p>No user found with that username. Please try again.</p>";

$stmt->close();
$conn->close();
fclose($logHandle);
?>
