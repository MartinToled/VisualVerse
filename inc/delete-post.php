<?php
require '../php/connect.php';
session_start();

if (isset($_POST['postid'])) {
  $postid = $_POST['postid'];
  // Validate the post ID and check if the post belongs to the current user
  $stmt = $conn->prepare("SELECT user_id FROM uploads WHERE uploads_id = :postid");
  $stmt->bindParam(':postid', $postid);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result && $result['user_id'] == $_SESSION['id']) {
    // Delete the post from the database
    $stmt = $conn->prepare("DELETE FROM uploads WHERE uploads_id = :postid");
    $stmt->bindParam(':postid', $postid);
    $stmt->execute();

    // Optionally, you can also delete related comments or likes associated with the post
    $stmt = $conn->prepare("DELETE FROM likes WHERE post_like = :postid");
    $stmt->bindParam(':postid', $postid);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM comments WHERE post_id = :postid");
    $stmt->bindParam(':postid', $postid);
    $stmt->execute();

    echo "success";
    header("Location: ../php/profile.php");
  } else {
    echo "error";
    header("Location: ../php/profile.php?=errordelpost");
  }
} else {
  echo "error";
  header("Location: ../php/profile.php?=errordelpost");
}
?>
