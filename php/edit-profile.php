<?php
session_start();
require 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
  }

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    // Update the user's profile information in the database
    $userId = $_SESSION['id'];
    $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, username = :username, bio = :bio, email = :email WHERE id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    // Handle profile picture upload
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $profilePicture = $_FILES['file'];
        $profilePictureName = $profilePicture['name'];
        $profilePictureTmpName = $profilePicture['tmp_name'];
        $profilePictureSize = $profilePicture['size'];
        $profilePictureType = $profilePicture['type'];

        // Process the uploaded profile picture and update the file path in the database
        // ...

        // Example code to move the uploaded file to a desired location
        $profilePictureLocation = '../php/uploads/' . $profilePictureName;
        move_uploaded_file($profilePictureTmpName, $profilePictureLocation);

        // Update the profile picture path in the database
        $sql = "UPDATE users SET pfp = :profilePictureLocation WHERE id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':profilePictureLocation', $profilePictureLocation);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }

    // Handle header picture upload
    if ($_FILES['header']['error'] === UPLOAD_ERR_OK) {
        $headerPicture = $_FILES['header'];
        $headerPictureName = $headerPicture['name'];
        $headerPictureTmpName = $headerPicture['tmp_name'];
        $headerPictureSize = $headerPicture['size'];
        $headerPictureType = $headerPicture['type'];

        // Process the uploaded header picture and update the file path in the database
        // ...

        // Example code to move the uploaded file to a desired location
        $headerPictureLocation = '../php/uploads/' . $headerPictureName;
        move_uploaded_file($headerPictureTmpName, $headerPictureLocation);

        // Update the header picture path in the database
        $sql = "UPDATE users SET header = :headerPictureLocation WHERE id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':headerPictureLocation', $headerPictureLocation);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }

    // Redirect back to the profile page after updating
    header('Location: profile.php');
    exit();
}

    // Retrieve the user's current profile information
    $userId = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $variable1 = " ";
    ?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../scss-css/edit.css">
        <link rel="icon" type="image/png" href="../images/VV.png">
        <title>Edit Profile</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/3a5712755a.js" crossorigin="anonymous"></script>
        <script src="../js/modal.js"></script>

    </head>

    <body>
<!-- HTML form to edit profile information -->
<a href="profile.php" id="back"><i class="fa-solid fa-circle-arrow-left" style="color:#46CDCF; font-size:3vw; margin-top:3vh"></i></a>
<form action="edit-profile.php" method="POST" enctype="multipart/form-data">
    <div id="edit-form">
    <h1>Edit Profile</h1>
        <br>
        <br>
        <br>
        <div id="container"> 
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo $user['firstname'];?>">
        <br>
        </div>

        <div id="container"> 
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>">
        <br>
        </div>

        <div id="container"> 
        <label for="lastname">Username</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>">
        <br>
        </div>

        <div id="container"> 
        <label for="bio">Bio</label>
        <input type="text" id="bio" name="bio" autocomplete="off" placeholder="Write your Bio..."><?php echo $user['bio']; ?></input>
        <br>
        </div>

        <div id="container"> 
        <label for="lastname">Email</label>
        <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>">
        <br>
        </div>

        <br>

        <div id="file-con">
        <label>Header Picture</label>
        <i class="fa-regular fa-folder-open" style="color:#28AFB0;  margin-left:3vw; "></i>
        <input id="pictures" type="file" accept="image/*" name="header" class="form-control" onchange="readURL(this, 'blah');" >
        </div>
        <img id="blah" src="#" alt="" ></img>

        <br>

        <div id="file-con">
            <label>Profile Picture</label>
            <i class="fa-regular fa-folder-open" style="color:#28AFB0;  margin-left:3vw "></i>
            <input id="pictures" type="file" accept="image/*" name="file" class="form-control" onchange="readURL(this, 'blah2');" >
        </div>
        <img id="blah2" src="#" alt="" style= "border-radius: 50%;"></img>
        
        <br>

        <input id="button" type="submit" value="Save Changes">
    </div>
</form>
</body>
</html>