<?php
session_start();
require_once("../Security/config.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $card_number = filter_input(INPUT_POST, 'cardNumberInput', FILTER_SANITIZE_SPECIAL_CHARS);
    $card_holder = filter_input(INPUT_POST, 'cardNameInput', FILTER_SANITIZE_SPECIAL_CHARS);
    $expiration = filter_input(INPUT_POST, 'cardExpirationInput', FILTER_SANITIZE_SPECIAL_CHARS);
    $cvv = filter_input(INPUT_POST, 'cardCVCInput', FILTER_SANITIZE_SPECIAL_CHARS);
    $amount = filter_input(INPUT_POST, 'amountInput', FILTER_VALIDATE_FLOAT);  // Updated to capture amountInput

    // Validate the input (basic validation for demo purposes)
    if (!$card_number || !$card_holder || !$expiration || !$cvv || !$amount) {
        die('Invalid input. Please fill all fields correctly.');
    }

    // Ensure card number and CVV are the correct length (basic validation)
    // Remove spaces from card number for accurate length checking
    $card_number_clean = str_replace(' ', '', $card_number);
    if (strlen($card_number_clean) < 16 || strlen($cvv) !== 3) {
        die('Invalid card details.');
    }

    // Only keep the last 4 digits of the card number
    $card_last_four = substr($card_number_clean, -4);

    // Generate a transaction reference (unique identifier for payment)
    $transaction_reference = uniqid('TRANS-');

    // Set the payment method to 'credit_card' (for example)
    $payment_method = 'credit_card';
    
    // Set the payment date to the current time
    $payment_date = date('Y-m-d H:i:s');

    // Set the initial payment status to 'pending'
    $payment_status = 'pending';

    // Create a database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check the connection and log any errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare an SQL statement to insert payment data
    $stmt = $conn->prepare("INSERT INTO payments (payment_method, card_holder_name, card_last_four, card_expiration, amount, payment_date, transaction_reference, payment_status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the parameters
    $stmt->bind_param("ssssdsss", $payment_method, $card_holder, $card_last_four, $expiration, $amount, $payment_date, $transaction_reference, $payment_status);

    // Execute the statement and check for execution errors
    if ($stmt->execute()) {
        // Now update the payment status to 'paid'
        $payment_id = $stmt->insert_id;  // Get the inserted payment ID

        // Update the payment status to 'paid'
        $update_stmt = $conn->prepare("UPDATE payments SET payment_status = 'paid' WHERE payment_id = ?");
        $update_stmt->bind_param("i", $payment_id);

        if ($update_stmt->execute()) {
            // Redirect to the ticket or success page after successful payment
            header("Location: ../Book flight/ticket.php");
            exit(); // Always call exit after header to ensure script stops
        } else {
            die("Error updating payment status: " . htmlspecialchars($update_stmt->error));
        }

        // Close the update statement
        $update_stmt->close();

    } else {
        // Log SQL error if execution fails
        die("Error processing payment: " . htmlspecialchars($stmt->error));
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>