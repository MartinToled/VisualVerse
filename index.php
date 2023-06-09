<!DOCTYPE html>

<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="scss-css/signin.css">
    <link rel="icon" type="image/png" href="favicon.ico">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/3a5712755a.js" crossorigin="anonymous"></script>
    </head>
    
    <body>
        <div id="form">
            <div id="input">
                <h2 id="text1">Log in</h2>
                <h3 id="welcome">Welcome back to <strong style="color:#28AFB0">VisualVerse</strong></h3>
                <form action="inc/login-includes.php" method= "POST">
                <?php 
                // Check if error parameter exists in the URL and display appropriate error message
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                    if ($error == "resetsucs") {
                        echo '<p class="error-msg">Reset Successfull!</p>';
                    }
                }
                ?>
                    <div id="in">
                        <label>Email</label>
                        <input id="inputs" type="text" placeholder=" " name="email"><br>
                    </div>
                    <div id="in">
                        <label>Password</label>
                        <input id="inputs" type="password" placeholder=" " name="password">
                    </div>
                    <?php 
                // Check if error parameter exists in the URL and display appropriate error message
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                    if ($error == "wrongpass") {
                        echo '<p class="error-msg">Invalid email or password.</p>';
                    }
                    if ($error == "emptyfield") {
                        echo '<p class="error-msg">Please fill in all fields.</p>';
                    }
                    if ($error == "invalidcredentials") {
                        echo '<p class="error-msg">Invalid email or password.</p>';
                    }
                }
                ?>
                    <input id="button" type="submit" value="Log in" name="login" style="font-family: 'Exo', sans-serif;">
                    <p id="regtxt">New to prompty? <a href="php/signup.php" id="reg">Register</a></p>
                </form>
               
            </div>
        </div>
        <div class="glitch">
  
</div>
    </body>

</html>