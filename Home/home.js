window.onload = function() {
    document.getElementById("appName").innerHTML = "Browser Name: " + navigator.appName;
    document.getElementById("appVersion").innerHTML = "Browser Version: " + navigator.appVersion;
    document.getElementById("platform").innerHTML = "Platform: " + navigator.platform;
    document.getElementById("userAgent").innerHTML = "User Agent: " + navigator.userAgent;
    document.getElementById("language").innerHTML = "Browser Language: " + navigator.language;
    const departureDate = document.getElementById('date').value;
    const today = new Date().toISOString().split('T')[0];
    departureDate.setAttribute('min', today);
}

// Function to set the number of columns
function setColumnCount() {
    const ticketsContainer = document.querySelector('.tickets-container');
    const width = window.innerWidth;

    // Determine the number of columns based on the width
    let columnCount;
    if (width >= 1200) {
        columnCount = 4; //  large screens
    } else if (width >= 768) {
        columnCount = 3; // medium screens
    } else {
        columnCount = 1; // small screens
    }

    // Update the CSS property to change the number of columns
    ticketsContainer.style.gridTemplateColumns = `repeat(${columnCount}, 1fr)`;
}

// Set initial column count on page load
setColumnCount();

// Update column count on window resize
window.addEventListener('resize', setColumnCount);

