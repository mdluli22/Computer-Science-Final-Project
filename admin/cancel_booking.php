<?php
session_start();
require_once("../Home/config.php");

// Create a database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle booking cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $cancelBookingId = intval($_POST['cancel_booking_id']);
    $cancelSql = "UPDATE bookings SET payment_status = 'cancelled' WHERE booking_id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($cancelSql);
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("i", $cancelBookingId);
    
    // Execute the statement
    if (!$stmt->execute()) {
        die("Execute failed: " . htmlspecialchars($stmt->error));
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();

// Redirect or provide feedback as necessary
header("Location: admin.php");
exit();
?>
