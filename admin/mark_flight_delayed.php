
<?php
session_start();
require_once("../Home/config.php");

$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if flight_id is provided
if (isset($_POST['flight_id'])) {
    $flightId = $_POST['flight_id'];

    // Update flight status to 'delayed'
    $sql = "UPDATE flights SET status = 'delayed' WHERE flight_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $flightId);
    
    if ($stmt->execute()) {
        echo "Flight marked as delayed.";
    } else {
        echo "Error marking flight as delayed: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
