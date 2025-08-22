<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Syntax On Air</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <?php 
        require_once("../header/header.php");
    ?>

    <main>
        <section class="auth-form">

            <h2>Sign In</h2>

            <form class="form-container" action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username">
                </div>
            
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                </div>
            
                <button type="submit">Submit</button>
            </form>
            
            <p>Don't have an account? <a href="signUp.php">Sign Up</a></p>
            <p>Admin log in <a href="agent.php">here.</a></p>
        </section>
    </main>

    <?php
        require_once("../footer/footer.php");
    ?>
</body>
</html>



