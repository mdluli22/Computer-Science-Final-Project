<?php
session_start();
// Include database configuration
require_once("../Home/config.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../Home/signin.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve review data from the form
    $review = $_POST['review'] ?? '';
    $rating = $_POST['rating'] ?? 0; // Assume rating is from 1 to 5

    // Create a database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL statement to insert the review
    $stmt = $conn->prepare("INSERT INTO company_review (username, review, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $review, $rating);

    if ($stmt->execute()) {
        echo "<p>Review submitted successfully!</p>";
    } else {
        echo "<p>Error submitting review: " . $stmt->error . "</p>";
    }

    // Close the statement
    $stmt->close();
}

// Create a new database connection to fetch past reviews
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL statement to fetch past reviews
$stmt = $conn->prepare("SELECT review, rating, created_at FROM company_review WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$reviews_result = $stmt->get_result();

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review | Syntax On Air</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

:root {
    --primary-color: #ff5a3c;
    --white: #ffffff;
    --light-gray: #f4f4f4;
    --dark-gray: #333333;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--light-gray);
    color: var(--dark-gray);
    line-height: 1.6;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

h1, h2 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    text-align: center;
    animation: fadeIn 1s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

form {
    background-color: var(--white);
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

textarea, select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-family: inherit;
    font-size: 1rem;
}

.btn {
    display: inline-block;
    background-color: var(--primary-color);
    color: var(--white);
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    background-color: #e64a2e;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.reviews-list {
    display: grid;
    gap: 1.5rem;
}


    </style>
</head>
<body>
    <h1>Submit Your Review</h1>
    <form method="POST" action="">
        <label for="review">Your Review:</label>
        <textarea id="review" name="review" rows="4" required></textarea>

        <label for="rating">Rating (1 to 5):</label>
        <select id="rating" name="rating" required>
            <option value="">Select Rating</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>

        <button type="submit">Submit Review</button>
    </form>
    
    <h2>Your Past Reviews</h2>
    <div class="reviews-list">
        <?php
        // Display past reviews
        if ($reviews_result->num_rows > 0) {
            while ($review = $reviews_result->fetch_assoc()) {
                ?>
                <div class="review-card">
                    <p><strong>Rating:</strong> <?php echo htmlspecialchars($review['rating']); ?> / 5</p>
                    <p><strong>Review:</strong> <?php echo htmlspecialchars($review['review']); ?></p>
                    <p><em>Submitted on: <?php echo htmlspecialchars(date('d M Y', strtotime($review['created_at']))); ?></em></p>
                </div>
                <hr>
                <?php
            }
        } else {
            echo "<p>No reviews submitted yet.</p>";
        }
        ?>
    </div>
    
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
