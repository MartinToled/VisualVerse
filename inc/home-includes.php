<?php
session_start();
require '../php/connect.php';

if(isset($_POST['submit'])) {
    $uploads_id = $_POST['uploads_id'] ?? null;
    $caption = ($_POST['caption']);
    $tags = $_POST['tags'];
    $tagString = json_encode($tags);
    $tag = strtoupper($tagString);
   // Count total files
    $countfiles = count($_FILES['files']['name']);

    $user_id = $_SESSION['id'];
    $user_name = $_SESSION['username'];
    if (empty($tags)) {
        // Tags field is empty, set the error message
        $_SESSION['error'] = "Choose a Tag.";
        header("Location: ../php/home.php");
      }    else{
    // Prepared statement
    $query = "INSERT INTO uploads (uploads_id, caption,  tag, name, user_id, user_name, images) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $statement = $conn->prepare($query);
    $statement->bindParam(1, $uploads_id);
    $statement->bindParam(2, $caption);
    $statement->bindParam(3, $tag);
    $statement->bindParam(4, $filename);
    $statement->bindParam(5, $user_id);
    $statement->bindParam(6, $user_name);
    $statement->bindParam(7, $target_file);

    // Loop all files
    if (!empty($_FILES['files']['name'][0])) {
    
    for ($i = 0; $i < $countfiles; $i++) {
        $filename = $_FILES['files']['name'][$i];
        $target_file = '../php/uploads/' . $filename;
        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        $valid_extension = array("png", "jpeg", "jpg", "gif", "webp", "mp4");

        if (in_array($file_extension, $valid_extension)) {
            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $target_file)) {
                $statement->execute(array($uploads_id,$caption, $tag,$filename,$user_id,$user_name,$target_file));
                    }
                }
            }
            
        } else{
        $query = "INSERT INTO uploads (user_id, user_name, caption, tag) VALUES (?,?,?,?)";

        // Prepare the statement
        $stmt = $conn->prepare($query);

        // Bind the caption and tags as parameters
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $user_name);
        $stmt->bindParam(3, $caption);
        $stmt->bindParam(4, $tag);

        // Execute the statement
        $stmt->execute();
    }
        echo "File upload successfully";
        header("Location: ../php/home.php");
        die();
    }
}
    else{
        echo "<div>";
        echo "<p>Something is wrong with your post. Please try again later!</p>";
        echo "</div>";
    }
?>