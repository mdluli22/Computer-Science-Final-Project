<?php
session_start();
$_SESSION = []; // Clear all session variables
session_destroy(); // Destroy the session
header("Location: ../Home/home.php"); // Redirect after logout
exit();
?>

<script>


let timeout;

function resetTimer() {
    clearTimeout(timeout);
    timeout = setTimeout(logout, 300000); // 300000 ms = 5 minutes
}

function logout() {
    window.location.href = '../Security/logout.php'; // Your logout URL
}

// Initialize the timeout when the DOM content is loaded
document.addEventListener("DOMContentLoaded", resetTimer);
document.onmousemove = resetTimer;
document.onkeypress = resetTimer;
</script>

