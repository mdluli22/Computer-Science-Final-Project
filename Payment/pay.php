<?php

session_start();
require_once("../Security/config.php");

// Check if the total price is stored in the session
if (!isset($_SESSION['total_price'])) {
    // Display an error message if no total price is provided and stop further execution
    echo "<h2 style='color:red;'>Error: Total price is not set. Please go back and complete the booking process.</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Credit Card Payment</title>
  <link rel="stylesheet" href="pay.css">
</head>
<body>
  <div class="container">
    <h1>Credit Card Payment</h1>
    <p>Total Amount: R <?php echo number_format($_SESSION['total_price'], 2); ?></p> <!-- Display the total price -->

    <!-- Payment Form -->
    <form id="paymentForm" action="payment.php" method="post">
      <div class="credit-card">
        <div class="card-front">
          <div class="card-logo">Credit Card</div>
          <div class="card-number" id="cardNumber">•••• •••• •••• ••••</div>
          <div class="card-info">
            <div class="card-holder">
              <span>Card Holder</span>
              <div id="cardName">Your Name</div>
            </div>
            <div class="card-expiration">
              <span>Expires</span>
              <div id="cardExpiration">MM/YY</div>
            </div>
          </div>
        </div>
        <div class="card-back">
          <div class="card-stripe"></div>
          <div class="card-cvc">
            <span>CVC</span>
            <div id="cardCVC">•••</div>
          </div>
        </div>
      </div>
      
      <!-- Form fields with required attributes -->
      <div class="form-group">
        <label for="cardNumberInput">Card Number</label>
        <input type="text" id="cardNumberInput" name="cardNumberInput" maxlength="19" placeholder="1234 5678 9012 3456" required>
      </div>
      <div class="form-group">
        <label for="cardNameInput">Card Holder Name</label>
        <input type="text" id="cardNameInput" name="cardNameInput" placeholder="Your Name" required>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="cardExpirationInput">Expiration Date</label>
          <input type="text" id="cardExpirationInput" name="cardExpirationInput" maxlength="5" placeholder="MM/YY" required>
        </div>
        <div class="form-group">
          <label for="cardCVCInput">CVC</label>
          <input type="text" id="cardCVCInput" name="cardCVCInput" maxlength="3" placeholder="123" required>
        </div>
      </div>
      <div class="form-group">
        <label for="amountInput">Payment Amount (R)</label>
        <input type="number" id="amountInput" name="amountInput" min="1" step="0.01" value="<?php echo $_SESSION['total_price']; ?>" required readonly> <!-- Show the amount and make it read-only -->
      </div>
      <button type="submit">Process Payment</button>
    </form>
  </div>

  <!-- Loading overlay for payment processing feedback -->
  <div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
    <p>Processing your payment...</p>
  </div>
  
  <!-- JavaScript file -->
  <script src="pay.js"></script>
</body>
</html>
