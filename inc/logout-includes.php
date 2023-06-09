<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="session.css">
</head>
<body>

<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();
header('location: ../index.php');
?>

</body>
</html>