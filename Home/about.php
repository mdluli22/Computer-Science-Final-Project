<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syntax On Air</title>
    <link rel="icon" type="image/x-icon" href="images/FullLogo_Transparent_NoBuffer.png">
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <?php 
            require_once("../header/header.php");
        ?>

        <main>
            <section class="profiles">
                <div>
                    <h3>August</h3>
                    <img src="images/AugustPP.jpg" alt="August's Photo" />
                    <embed src="images/August.mp4">
                    <p>August is the Chief Executive Officer (CEO) of Syntax On Air, leading the company with a vision for innovation and excellence...</p>
                </div>
        
                <div>
                    <h3>Akhona</h3>
                    <img src="images/AkhonaPP.JPG" alt="Akhona's Photo" />
                    <embed src="images/AkhonaV.mp4">
                    <p>Akhona serves as the Chief Financial Officer (CFO) at Syntax On Air, overseeing all financial aspects of the organization...</p>
                </div>
        
                <div>
                    <h3>Calvin</h3>
                    <img src="images/CalvinPP.JPG" alt="Calvin's Photo" />
                    <embed src="images/calvin.mp4">
                    <h1 style="color: red;">FIRED!!</h1>
                </div>
            </section>
            <?php require_once("../footer/footer.php"); ?>
        </main>        
        
    </div>
</body>
</html>

