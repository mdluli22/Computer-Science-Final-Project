<?php
session_start();
require_once("../Home/config.php");

// Create a database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check if connection was successful
if ($conn->connect_error) {
    die("<p class=\"error\">Connection to the database failed: " . $conn->connect_error . "</p>");
}

// Check if the passenger ID is provided
if (isset($_POST['passenger_id'])) {
    $passenger_id = intval($_POST['passenger_id']);

    // Update the passenger's is_deleted status to 1 (soft delete)
    $sql = "UPDATE passengers SET is_deleted = 1 WHERE passenger_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $passenger_id);
    
    if ($stmt->execute()) {
        // Redirect or provide success message
        $_SESSION['message'] = "Passenger soft-deleted successfully.";
        header("Location: admin.php"); // Redirect to your admin dashboard or wherever you need
        exit;
    } else {
        $_SESSION['error'] = "Failed to soft-delete the passenger.";
    }
}

$stmt->close();
$conn->close();
?>
