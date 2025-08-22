<?php
session_start();
require_once("../Home/config.php");

$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check if connection was successful
if ($conn->connect_error) {
    die("<p class=\"error\">Connection to the database failed: " . $conn->connect_error . "</p>");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['flight_id'])) {
    $flight_id = intval($_POST['flight_id']);
    
    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM flights WHERE flight_id = ?");
    $stmt->bind_param("i", $flight_id);
    
    if ($stmt->execute()) {
        echo "Flight deleted successfully.";
    } else {
        echo "Error deleting flight: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();

    // Redirect back to the flight management page
    header("Location: admin.php"); // Adjust the location as necessary
    exit();
}
?>
