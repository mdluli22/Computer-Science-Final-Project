<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syntax On Air</title>
    <link rel="icon" type="image/x-icon" href="images/FullLogo_Transparent_NoBuffer.png">
    <link rel="stylesheet" href="home.css">
    <script src="home.js"></script>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <?php 
            require_once("../header/header.php");
        ?>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Are you ready for take off?</h1>
                <p>With the world's best business class airline</p>
				<p>commited to a continuous improvement of provided services quality.</p>
                <div class="search-bar">
                    <select>
                        <option>Location</option>
                        <option value="tambo">O.R Tambo International Airport</option>
                        <option value="capetown">Cape Town International Airport</option>
                        <option value="durban">King Shaka International Airport</option>
                        <option value="bloem">Bloemfontein International Airport</option>
                        <option value="portE">Port Elizabeth International Airport</option>
                    </select>
                    
                    <label for="date" class="input-label"></label>
                    <input type="date" id="date" placeholder="Date">
                    
                    <!-- <button class="search-btn"><a href="../Book flight/book.php">Book Flight</a></button> CHANGE THIS  -->
                    <button class="search-btn"><a href="../Book flight/flightSchedule.php">Schedules</a></button>
                </div>
            </div>
        </section>

        <section class="tickets-special">
            <h2>Tickets on Special</h2>
            <div class="tickets-container">
                <div class="ticket-card">
                    <div class="airline">Etihad Airways</div>
                    <div class="flight-info">
                        <span>SUB → DPS</span>
                        <span>Direct | 55m</span>
                    </div>
                    <div class="price">R34.92/Pax</div>
                    <div class="special-tag">Free Reschedule</div>
                </div>
                <div class="ticket-card">
                    <div class="airline">Citilink</div>
                    <div class="flight-info">
                        <span>SUB → DPS</span>
                        <span>Direct | 55m</span>
                    </div>
                    <div class="price">R41.01/Pax</div>
                    <div class="special-tag">Free Protection</div>
                </div>
                <div class="ticket-card">
                    <div class="airline">Lion Air</div>
                    <div class="flight-info">
                        <span>SUB → DPS</span>
                        <span>Direct | 55m</span>
                    </div>
                    <div class="price">R29.50/Pax</div>
                    <div class="special-tag">Discounted Fare</div>
                </div>
            </div>
        </section>

        <section class="vid-table">
            <h2>Travel Tips</h2>
            <table class="for-video">
                <tr>
                    <td class="prep">
                        <h2>Are you nervous about flight abroad?</h2>
                        <h3>Well here are some tips which will help you feel like you are better prepared.</h3>
                    </td>
                    <td>
                        <aside class="vid">
                            <iframe src="https://www.youtube.com/embed/k97G2YC3I60"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope;
                            picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen></iframe>
                        </aside>
                    </td>
                </tr>
                <tr>
                    <td>
                        <iframe src="https://www.youtube.com/embed/HhaJBpNr5zs"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope;
                        picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </td>
                    <td>
                        <h2>Don't know how and what to pack to keep your bag small?</h2>
                        <h3>Here are tips are tricks on how to keep pack your bag and avoid long queues.</h3>
                    </td>
                </tr>
            </table>
        </section>
        
        <aside>
            <div class="tips"><a href="https://www.flysaa.com/manage-fly/baggage/restricted-items">Restricted Items</a></div>
        </aside>        

        <footer>
            <p>Contact Us</p><br>
            <p>&copy; 2024 Syntax On Air. All rights reserved.</p>
            <!-- Browser Info Section -->
            <div id="browser-info">
                <h3>Browser Information</h3>
                <p id="appName"></p>
                <p id="appVersion"></p>
                <p id="platform"></p>
                <p id="userAgent"></p>
                <p id="language"></p>
            </div>
        </footer>        
    </div>
</body>
</html>
