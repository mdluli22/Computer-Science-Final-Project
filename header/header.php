<header>
    <img src="../images/FullLogo_Transparent_NoBuffer.png" alt="Syntax On Air Logo" class="logo">
    
    <nav>
        <ul class="nav-links">
            <li><a href="../Home/home.php">Home</a></li>
            <li><a href="../Home/about.php">About Us</a></li>
            <li><a href="../Home/review.php">Reviews</a></li>
            <li><a href="../Home/help.php">Help</a></li>
        </ul>
        <div class="user-actions">
            
            <a href="signin.php" class="sign-in-btn">Sign In</a>
            <a href="signUp.php" class="sign-up-btn">Sign Up</a>
        </div>
    </nav>

    <!-- for burger menu for mobile -->
    <input type="checkbox" id="burger-toggle" />
    <label for="burger-toggle" class="burger">
        <div></div>
        <div></div>
        <div></div>
    </label>

    <div class="mobile-menu">
        <a href="../Home/home.php">Home</a>
        <a href="../Home/about.php">About Us</a>
        <a href="../Home/review.php">Reviews</a>
        <a href="../Home/help.php">Help</a>
    </div>
</header>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


    /* Header */
header {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: white;
    box-shadow: 0 0.25rem 0.375rem rgba(0, 0, 0, 0.1);
    height: 5.5rem;
    z-index: 1000; /* Ensure it stays on top of other content */
}

header .logo {
    height: 50px; /* Adjust the height as needed */
    max-width: 100%; /* Ensure logo is responsive */
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 1.875rem;
    justify-content: center;
    flex: 2;
}

.nav-links li a {
    text-decoration: none;
    color: black;
    font-weight: 500;
    transition: color 0.3s ease, background-color 0.3s ease;
    padding: 0.5rem 1rem; /* Add padding for spacing around the text */
    border-radius: 5px; /* Optional: round the corners of the square */
}

.nav-links li a:hover {
    color: #fff; /* Change text color on hover */
    background-color: #ff5a3c; /* Change background color on hover */
}

.user-actions {
    display: flex;
    align-items: center;
    gap: 0.9375rem;
    justify-content: flex-end;
}

.sign-in-btn,
.sign-up-btn {
    padding: 0.625rem 1.25rem;
    border: none;
    background-color: #ff5a3c;
    color: white;
    border-radius: 1.25rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.sign-in-btn:hover,
.sign-up-btn:hover {
    background-color: #5a6268;
}

/* Burger Menu */
.burger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    margin-right: 1.25rem;
}

.burger div {
    width: 25px;
    height: 3px;
    background-color: #ff5a3c;
    margin: 4px;
}

/* Hide Checkbox */
#burger-toggle {
    display: none;
}

/* Mobile Menu */
.mobile-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    width: 100%;
    background-color: white;
    flex-direction: column;
    padding: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.mobile-menu a {
    padding: 1rem 0;
    color: #ff5a3c;
    text-align: center;
    text-decoration: none;
}

/* Show Mobile Menu When Checkbox is Checked */
#burger-toggle:checked ~ .mobile-menu {
    display: flex;
}

#burger-toggle:checked ~ nav ul {
    display: none;
}

/* Media Query for Responsive Navigation */
@media (max-width: 1200px) {
    .nav-links {
        display: none;
    }

    .burger {
        display: flex;
    }
}
</style>