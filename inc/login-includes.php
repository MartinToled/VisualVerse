<?php
require '../php/connect.php';

// Check if the login form was submitted
if (!isset($_POST['login'])) {
    header("Location: ../index.php?error");
    exit();
}
// Process the login request
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../index.php?error=emptyfield");
        exit();
    }

    try {
        // Retrieve user information from the database
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        // Verify the password
        if ($row = $stmt->fetch()) {
            $verifyPass = password_verify($password, $row['password']);
            // Redirect if the password is incorrect
            if ($verifyPass == false) {
                header("Location: ../index.php?error=wrongpass");
                exit();
            } elseif ($verifyPass == true) { 
                // Start a session and store user information
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: ../php/profile.php?successlogin");
                exit();
            }
        } else {
              // Redirect if the user is not found
            header("Location: ../index.php?error=invalidcredentials");
            exit();
        }
    } catch (PDOException $e) {
        // Handle the exception (e.g., log the error, show a generic error message)
        header("Location: ../index.php?error=database");
        exit();
    }
}
?>
