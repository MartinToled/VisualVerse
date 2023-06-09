<?php
require '../php/connect.php';

if (!isset($_POST['login'])) {
    header("Location: ../index.php?error");
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../index.php?error=emptyfield");
        exit();
    }

    try {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            $verifyPass = password_verify($password, $row['password']);

            if ($verifyPass == false) {
                header("Location: ../index.php?error=wrongpass");
                exit();
            } elseif ($verifyPass == true) {
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: ../php/profile.php?successlogin");
                exit();
            }
        } else {
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
