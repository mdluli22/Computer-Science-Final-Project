document.addEventListener('DOMContentLoaded', () => {
  const creditCard = document.querySelector('.credit-card');
  const cardNumber = document.getElementById('cardNumber');
  const cardName = document.getElementById('cardName');
  const cardExpiration = document.getElementById('cardExpiration');
  const cardCVC = document.getElementById('cardCVC');

  const cardNumberInput = document.getElementById('cardNumberInput');
  const cardNameInput = document.getElementById('cardNameInput');
  const cardExpirationInput = document.getElementById('cardExpirationInput');
  const cardCVCInput = document.getElementById('cardCVCInput');

  const form = document.getElementById('paymentForm');
  const loadingOverlay = document.getElementById('loadingOverlay');

  function formatCardNumber(value) {
    const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    const matches = v.match(/\d{4,16}/g);
    const match = matches && matches[0] || '';
    const parts = [];

    for (let i = 0, len = match.length; i < len; i += 4) {
      parts.push(match.substring(i, i + 4));
    }

    if (parts.length) {
      return parts.join(' ');
    } else {
      return value;
    }
  }

  function formatExpiration(value) {
    const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    if (v.length >= 2) {
      return v.slice(0, 2) + '/' + v.slice(2, 4);
    }
    return v;
  }

  cardNumberInput.addEventListener('input', (e) => {
    const value = formatCardNumber(e.target.value);
    cardNumberInput.value = value;
    cardNumber.textContent = value || '•••• •••• •••• ••••';
  });

  cardNameInput.addEventListener('input', (e) => {
    cardName.textContent = e.target.value || 'Your Name';
  });

  cardExpirationInput.addEventListener('input', (e) => {
    const value = formatExpiration(e.target.value);
    cardExpirationInput.value = value;
    cardExpiration.textContent = value || 'MM/YY';
  });

  cardCVCInput.addEventListener('focus', () => {
    creditCard.classList.add('flipped');
  });

  cardCVCInput.addEventListener('blur', () => {
    creditCard.classList.remove('flipped');
  });

  cardCVCInput.addEventListener('input', (e) => {
    cardCVC.textContent = e.target.value || '•••';
  });

  // Form validation function
  function validateForm() {
    let isValid = true;
    const errorMessages = [];

    // Validate Card Number (16 digits, may include spaces)
    const cardNumberValue = cardNumberInput.value.replace(/\s/g, '');
    if (!/^\d{16}$/.test(cardNumberValue)) {
      isValid = false;
      errorMessages.push("Please enter a valid 16-digit card number.");
    }

    // Validate Card Name (not empty, only letters and spaces)
    if (!/^[a-zA-Z\s]+$/.test(cardNameInput.value.trim())) {
      isValid = false;
      errorMessages.push("Please enter a valid name (letters and spaces only).");
    }

    // Validate Expiration Date (MM/YY format)
    const expirationValue = cardExpirationInput.value;
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expirationValue)) {
      isValid = false;
      errorMessages.push("Please enter a valid expiration date (MM/YY).");
    } else {
      const [month, year] = expirationValue.split('/');
      const expirationDate = new Date(2000 + parseInt(year), parseInt(month) - 1);
      if (expirationDate < new Date()) {
        isValid = false;
        errorMessages.push("The card has expired. Please use a valid card.");
      }
    }

    // Validate CVC (3 or 4 digits)
    if (!/^\d{3,4}$/.test(cardCVCInput.value)) {
      isValid = false;
      errorMessages.push("Please enter a valid CVC (3 or 4 digits).");
    }

    return { isValid, errorMessages };
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the default form submission
    
    const { isValid, errorMessages } = validateForm();

    if (isValid) {
      // Show loading overlay
      loadingOverlay.style.display = 'flex';

      // Simulate processing delay (e.g., 3 seconds)
      setTimeout(() => {
        // Hide loading overlay
        loadingOverlay.style.display = 'none';
        
        // After 3 seconds, submit the form to payment.php
        form.submit(); // This will now submit the form to the action URL (payment.php)

        // Optionally reset the form fields and UI after submission
        form.reset();
        cardNumber.textContent = '•••• •••• •••• ••••';
        cardName.textContent = 'Your Name';
        cardExpiration.textContent = 'MM/YY';
        cardCVC.textContent = '•••';
      }, 3000); // Simulated delay for showing loading overlay
    } else {
      // Display error messages
      alert('Please correct the following errors:\n' + errorMessages.join('\n'));
    }
  });
});