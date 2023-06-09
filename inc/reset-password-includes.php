<?php
session_start();
require '../php/connect.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the email entered by the user
  $email = $_POST['email'];

  // Get the email associated with the current user
  $userId = $_SESSION['id'];
  $stmt = $conn->prepare("SELECT email FROM users WHERE id = :userId");
  $stmt->bindParam(':userId', $userId);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $userEmail = $row['email'];

  if ($email === $userEmail) {
    // Email matches, proceed to enter new password page
    $_SESSION['email'] = $email;
    header("Location: ../php/reset-pass.php");
    exit();
  } else {
    // Email does not match
    $_SESSION['password_reset_error'] = "Email does not match.";
    header("Location: ../php/password-reset.php");
    exit();
  }
}
?>
