<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <link rel="stylesheet" href="review.css">
</head>
<body>
    <?php 
        session_start();
        require_once("../header/header.php");
        require_once("../Home/config.php"); // Include database configuration

        // Create a database connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch all reviews from the company_review table
        $stmt = $conn->prepare("SELECT review, rating, username FROM company_review ORDER BY created_at DESC");
        $stmt->execute();
        $reviews_result = $stmt->get_result();
    ?>

    <main>
        <section class="feedback-section">
            <h2>Reviews</h2>
            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Reviewer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are any reviews
                    if ($reviews_result->num_rows > 0) {
                        // Loop through each review and display it
                        while ($review = $reviews_result->fetch_assoc()) {
                            // Create a string of stars based on the rating
                            $stars = str_repeat('⭐', $review['rating']);
                            ?>
                            <tr>
                                <td><?php echo $stars; ?></td>
                                <td><?php echo htmlspecialchars($review['review']); ?></td>
                                <td><?php echo htmlspecialchars($review['username']); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='3'>No reviews available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php 
        // Close the statement and connection
        $stmt->close();
        $conn->close();
        require_once("../footer/footer.php"); 
    ?>

    <!-- Link to JavaScript file -->
    <script src="review.js"></script>
</body>
</html>
