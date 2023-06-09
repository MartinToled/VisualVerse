<?php
session_start();
require '../php/connect.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/VV.png">
    <link rel="stylesheet" href="../scss-css/pass-res.css">
</head>
<body>
    
<?php if (isset($_SESSION['password_reset_error']) && $_SESSION['password_reset_error']): ?>
  <script>
    alert('<?php echo $_SESSION['password_reset_error']; ?>');
  </script>
<?php unset($_SESSION['password_reset_error']); endif; ?> <!-- Clear the password reset error from the session -->
    <h2>Reset Password</h2>
    <div id="form">
    <form method="post" action="../inc/reset-password-includes.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Reset Password">
    </form>
    </div>
</body>
</html>
