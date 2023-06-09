<?php
session_start();
require 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the new password entered by the user
  $newPassword = $_POST['new_password'];

  // Update the user's password in the database
  $userId = $_SESSION['id'];
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :userId");
  $stmt->bindParam(':password', $hashedPassword);
  $stmt->bindParam(':userId', $userId);
  $stmt->execute();

  // Clear the session and redirect to the login page
  session_unset();
  session_destroy();
  header("Location: ../index.php?error=resetsucs");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enter New Password</title>
  <link rel="icon" type="image/png" href="../images/VV.png">
  <link rel="stylesheet" href="../scss-css/res-pass.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/3a5712755a.js" crossorigin="anonymous"></script>
</head>
<body>
  <h2>Enter New Password</h2>
  <div id="form">
  <form method="POST" action="reset-pass.php">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required>
    <br>
    <input type="submit" value="Reset Password">
  </form>
  </div>
</body>
</html>
