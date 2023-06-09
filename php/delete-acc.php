<?php
session_start();
require 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Process the account deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete user's account along with its post, likes, etc.
    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("DELETE FROM uploads WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM likes WHERE liked_user = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM comments WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM passres WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Clear session and redirect to login page
    session_destroy();
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="icon" type="image/png" href="../images/VV.png">
    <link rel="stylesheet" href="../scss-css/delete-acc.css">
</head>
<body>
    <h1>Delete Account</h1>
    <p>Are you sure you want to delete your account?</p>

    <form method="POST">
        <input type="submit" value="Delete">
    </form>
</body>
</html>