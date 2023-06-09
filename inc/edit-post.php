<?php
session_start();
require '../php/connect.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
  }
  
  // Check if the postid is provided in the URL
  if (!isset($_GET['postid'])) {
    header("Location: ../php/profile.php");
    exit();
  }
  

$postid = $_GET['postid'];
$user_id = $_SESSION['id'];

// Check if the user has permission to edit the post
$stmt = $conn->prepare("SELECT * FROM uploads WHERE uploads_id = :postid AND user_id = :user_id");
$stmt->bindParam(':postid', $postid);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    // The user doesn't have permission to edit the post
    header("Location: ../php/profile.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated caption and selected tags from the form
    $caption = $_POST['caption'];
    $selectedTags = isset($_POST['tags']) ? $_POST['tags'] : [];

    // Convert the selected tags array to a string
    $tagsString = json_encode($selectedTags);

    // Check if a new image file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['file'];

        // Validate the uploaded image
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'mp4', 'webp', 'gif'];
        $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            $error = "Invalid file format.";
        } else {
            // Generate a unique filename for the uploaded image
            $filename = uniqid() . '.' . $extension;

            // Move the uploaded image to the desired location
            $destination = '../php/uploads/' . $filename;
            if (move_uploaded_file($image['tmp_name'], $destination)) {
                // Update the image path in the database
                $stmt = $conn->prepare("UPDATE uploads SET images = :image_path WHERE uploads_id = :postid");
                $stmt->bindParam(':image_path', $destination);
                $stmt->bindParam(':postid', $postid);

                if (!$stmt->execute()) {
                    // Error occurred while updating the post
                    $error = "Error occurred while updating the post.";
                }
            } else {
                // Error occurred while moving the uploaded image
                $error = "Error occurred while uploading the image.";
            }
        }
    }

    // Update the caption and tags in the database if no error occurred
    if (!isset($error)) {
        $stmt = $conn->prepare("UPDATE uploads SET caption = :caption, tag = :tags WHERE uploads_id = :postid");
        $stmt->bindParam(':caption', $caption);
        $stmt->bindParam(':tags', $tagsString);
        $stmt->bindParam(':postid', $postid);

        if ($stmt->execute()) {
            // Redirect to the profile page
            header("Location: ../php/profile.php");
            exit();
        } else {
            // Error occurred while updating the post
            $error = "Error occurred while updating the post.";
        }
    }
}

// Get the tags for the post
$postTags = json_decode($row['tag']);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="icon" type="image/png" href="../images/VV.png">
    <link rel="stylesheet" href="../scss-css/post-edit.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/3a5712755a.js" crossorigin="anonymous"></script>
    <script src="../js/modal.js"></script>
</head>
<body>
<a href="../php/profile.php" id="back"><i class="fa-solid fa-circle-arrow-left" style="color:#46CDCF; font-size:3vw; margin-top:3vh"></i></a>
    <?php if (isset($error)): ?>
        <p><?=$error?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div id="edit-form">
        <h1>Edit Post</h1>
        <div id="caption-contain">
            <label for="caption">Caption</label>
            <textarea name="caption" id="caption" required><?=$row['caption']?></textarea>
        </div>
        <div id="tags">
            <label>Tags &nbsp</label>
            <div>
            <label>| </label>
                <input type="checkbox" name="tags[]" value="MUSIC" <?=(in_array('MUSIC', $postTags)) ? 'checked' : ''?>>
                <label>MUSIC | &nbsp</label>
            </div>
            <div>
                <label>| </label>
                <input type="checkbox" name="tags[]" value="ART" <?=(in_array('ART', $postTags)) ? 'checked' : ''?>>
                <label>ART | &nbsp</label>
            </div>
            <div>
                <label>| </label>
                <input type="checkbox" name="tags[]" value="MOVIE" <?=(in_array('MOVIE', $postTags)) ? 'checked' : ''?>>
                <label>MOVIE | &nbsp</label>
            </div>
            <div>
                <label>| </label>
                <input type="checkbox" name="tags[]" value="ANIME" <?=(in_array('ANIME', $postTags)) ? 'checked' : ''?>>
                <label>ANIME | &nbsp</label>
            </div>
            <div>
                <label>| </label>
                <input type="checkbox" name="tags[]" value="KPOP" <?=(in_array('KPOP', $postTags)) ? 'checked' : ''?>>
                <label>KPOP | &nbsp</label>
            </div>
            <div>
                <label>| </label>
                <input type="checkbox" name="tags[]" value="SPORTS" <?=(in_array('SPORTS', $postTags)) ? 'checked' : ''?>>
                <label>SPORTS | &nbsp</label>
            </div>
            <div>
                <label>| </label>
                <input type="checkbox" name="tags[]" value="TECHNOLOGY" <?=(in_array('TECHNOLOGY', $postTags)) ? 'checked' : ''?>>
                <label>TECH | &nbsp</label>
            </div>
            <div>
                <label>| </label>
                <input type="checkbox" name="tags[]" value="BLOG" <?=(in_array('BLOG', $postTags)) ? 'checked' : ''?>>
                <label>BLOG | &nbsp</label>
            </div>
        </div>
        <div id="image-contain">
            <label for="image">Current Image/Video</label>
            <br>
            <?php if ($row['images']): ?>
                <?php if (pathinfo($row['images'], PATHINFO_EXTENSION) === 'mp4'): ?>
                    <video width="400" controls>
                        <source src="<?=$row['images']?>" type="video/mp4">
                    </video>
                <?php else: ?>
                    <img src="<?=$row['images']?>" width="400" alt="Current Image">
                <?php endif; ?>
            <?php else: ?>
                <p style="opacity:0.5; font-size:1vw">No current image/video</p>
            <?php endif; ?>
        </div>
        <div id="file-con">
        <label>New Image/Video</label>
        <i class="fa-regular fa-folder-open" style="color:#28AFB0; font-size: 1.5vw; margin-left:3vw; "></i>
        <input id="pictures" type="file" accept="image/*" name="file" class="form-control" onchange="readURL(this, 'blah');" >
        </div>
        <img id="blah" src="#" alt="" ></img>
        <div>
            <button type="submit">Update</button>
        </div>
        </div>
    </form>
</body>
</html>
