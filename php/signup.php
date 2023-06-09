<!--HTML-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up</title>
        <link rel="stylesheet" href="../scss-css/signup.css">
        <link rel="icon" type="image/png" href="../images/VV.png">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://kit.fontawesome.com/3a5712755a.js" crossorigin="anonymous"></script>
        <script src="../js/modal.js"></script>
    </head>
        
    <body>

            <div class="bubbles">
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
</div>

    <div id="form">
            <div id="input">
                <h2 id="text1">Sign up</h2>
                <p id="welcome">Welcome to <strong style="color:#28AFB0">VisualVerse</strong></p>
                <?php
                    if (isset($_GET['error'])) {
                        $error = $_GET['error'];
                        // Display error message based on the error code
                        if ($error == 'emptyfild') {
                            echo "<p class='error-message'>Please fill in all the fields.</p>";
                        } elseif ($error == 'brokenemail') {
                            echo "<p class='error-message'>Invalid email address.</p>";
                        } elseif ($error == 'usernamebroken') {
                            echo "<p class='error-message'>Invalid username. Only alphanumeric characters are allowed.</p>";
                        } elseif ($error == 'passwordcheckwrong') {
                            echo "<p class='error-message'>Passwords do not match.</p>";
                        } elseif ($error == 'usernametaken') {
                            echo "<p class='error-message'>Username is already taken.</p>";
                        } elseif ($error == 'emialalreadyused') {
                            echo "<p class='error-message'>Email is already in use.</p>";
                        } elseif ($error == 'databaseconnectionfail') {
                            echo "<p class='error-message'>Database connection failed.</p>";
                        }
                    }
                ?>
                <form method='POST' action="../inc/signup-includes.php" enctype="multipart/form-data">
                <div id="images">
                <div id="file-con">
                    <label>Header Picture</label>
                    <i class="fa-regular fa-folder-open" style="color:#28AFB0; margin-left:3vw"></i>
                    <input id="pictures" type="file" accept="image/*" name="header" class="form-control" onchange="readURL(this, 'blah');" required>
                    <img id="blah" src="#" alt=""></img>   
                </div>



                    <div id="file-con">
                        <label>Profile Picture</label>
                        <i class="fa-regular fa-folder-open" style="color:#28AFB0; margin-left:3vw;"></i>
                        <input id="pictures" type="file" accept="image/*" name="file" class="form-control" onchange="readURL(this, 'blah2');" required>
                        <img id="blah2" src="#" alt="" style=" border-radius:50%;"></img>
                    </div>
                </div>
                <div id="in">
                    <input id="inputs" type="text" placeholder="First name" name="firstname" required>
                </div>
                    <div id="in">
                    <input id="inputs" type="text" placeholder="Last name" name="lastname" required>
                </div>
                <div id="in">
                    <input id="inputs" type="text" placeholder="Username" name="username" required>
                </div>

                <div id="in">
                    <input id="inputs" type="text" placeholder="Email" name="email" required>
                </div>
                <div id="in">
                    <input id="inputs" type="password" placeholder="Password" name="password" required>
                </div>
                <div id="in">
                    <input id="inputs" type="password" placeholder="Retype Password" name="reppass" required>
                </div>
                    <input id="button"type="submit" id="button" value="Sign up" name="submit">
                    <p id="regtxt">Already have an account? <a href="../index.php" id="reg">Log in</a></p>
                </form>
                
            </div>
        </div>
    </body>

</html>