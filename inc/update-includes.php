<?php
session_start();
require 'connect.php';

if (isset($_POST['submit'])) {
  // Get the user's updated profile information
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  // Retrieve and handle other form fields for profile information

  // Update the profile information in the database
  $updateStmt = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname WHERE id = :user_id");
  $updateStmt->bindParam(':firstname', $firstname);
  $updateStmt->bindParam(':lastname', $lastname);
  $updateStmt->bindParam(':user_id', $_SESSION['id']);
  $updateStmt->execute();

  // Handle profile picture and header picture uploads (similar to signup-includes.php)

  // Redirect back to the profile page after updating
  header("Location: profile.php");
  exit();
}
?>
