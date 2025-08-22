<?php
session_start();
// Include database configuration
require_once("../Home/config.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: ../Home/signin.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Create a database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details including passenger_id using the username from the session
$stmt = $conn->prepare("SELECT passenger_id, first_name, last_name, email, phone_number, passport_number, nationality, date_of_birth FROM passengers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    // Fetch user data
    $user = $result->fetch_assoc();
    
    // Set passenger_id in the session
    $_SESSION['passenger_id'] = $user['passenger_id'];
    
    $full_name = $user['first_name'] . ' ' . $user['last_name'];
    $email = $user['email'];
    $phone_number = $user['phone_number'] ?: 'Not Available';
    $passport_number = $user['passport_number'];
    $nationality = $user['nationality'];
    $date_of_birth = date('d M Y', strtotime($user['date_of_birth']));
} else {
    // If user does not exist, handle error (optional)
    $full_name = "Guest";
    $email = "Not Available";
    $phone_number = "Not Available";
    $passport_number = "Not Available";
    $nationality = "Not Available";
    $date_of_birth = "Not Available";
}

// Fetch booked flights for the current user (passenger_id)
$passenger_id = $_SESSION['passenger_id'];
$stmt = $conn->prepare("SELECT booking_id, departure, destination, departure_date, return_date, class, total_price FROM bookings WHERE passenger_id = ?");
$stmt->bind_param("i", $passenger_id);
$stmt->execute();
$flights_result = $stmt->get_result();

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
    <aside class="sidebar">
    <div class="profile">
        <img src="https://via.placeholder.com/100" alt="Profile Image" class="profile-image">
        <h2><?php echo htmlspecialchars($full_name); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone_number); ?></p>
        <p><strong>Nationality:</strong> <?php echo htmlspecialchars($nationality); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($date_of_birth); ?></p>
    </div>
    <nav>
        <ul>
            <li class="active"><i class="fas fa-home"></i> Booking</li>
            <li><a href="submit_review.php"><i class="fas fa-star"></i> Submit Review</a></li> <!-- Added review button -->
        </ul>
    </nav>
    <div class="logout-container">
        <form action="../Security/logout.php" method="POST">
            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log Off</button>
        </form>
    </div>
</aside>


        <main class="main-content">
            <button class="add-flight-btn" onclick="window.location.href='../Book flight/book.php'">Book New Flight</button>

            <section class="flight-section">
                <h2>Your Booked Flights</h2>
                <div class="flights-list">

                    <?php
                    // Check if the user has any flights
                    if ($flights_result->num_rows > 0) {
                        // Loop through each flight and display it
                        while ($flight = $flights_result->fetch_assoc()) {
                            ?>
                            <div class="flight-card" onclick="window.location.href='../Book flight/ticket.php'">
                                <div class="flight-details" onclick="window.location.href='../Book flight/ticket.php'">
                                    <div class="departure" onclick="window.location.href='../Book flight/ticket.php'">
                                        <div class="airport-info">
                                            <h2><?php echo htmlspecialchars($flight['departure']); ?></h2>
                                            <p>Departure City</p>
                                        </div>
                                        <div class="time-info">
                                            <p><?php echo htmlspecialchars(date('D d M', strtotime($flight['departure_date']))); ?></p>
                                            <p><?php echo htmlspecialchars(date('h:i A', strtotime($flight['departure_date']))); ?></p>
                                        </div>
                                    </div>
                                    <div class="flight-icon-container">
                                        <span class="flight-line-icon">&#x2014;</span>
                                        <div class="flight-icon" onclick="window.location.href='../Book flight/ticket.php'">&#9992;</div>
                                    </div>
                                    <div class="arrival">
                                        <div class="arrival-info">
                                            <h2><?php echo htmlspecialchars($flight['destination']); ?></h2>
                                            <p>Destination City</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="duration" onclick="window.location.href='../Book flight/ticket.php'">Class: <?php echo htmlspecialchars($flight['class']); ?></p>
                                <div class="flight-meta">
                                    <p class="price">R<?php echo htmlspecialchars(number_format($flight['total_price'], 2)); ?> Total</p>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No flights booked yet.</p>";
                    }
                    ?>
                </div>
            </section>
        </main>
    </div>
    <script src="dashboard.js"></script>
</body>
</html>
