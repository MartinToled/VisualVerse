<?php
require('../php/connect.php');
if (!isset($_POST['firstname'])){
    exit();
}

//checks if the firstname and lastname is empty and also if the email provided is valid
if(isset($_POST['submit'])) {
    //Grabbing the data
    $userid=intval($_GET['id']);
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $reppass = $_POST["reppass"];
    $file_name=$_FILES['file']['name'];
    $file_temp=$_FILES['file']['tmp_name'];
    $file_size=$_FILES['file']['size'];
    $file_type=$_FILES['file']['type'];

    $location='../php/uploads/'.$file_name;

        if(move_uploaded_file($file_temp, $location)){
            try{
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE users SET pfp ='$location' WHERE id = '$userid'";
                $conn->exec($sql);
            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }

    $file_name2=$_FILES['header']['name'];
    $file_temp2=$_FILES['header']['tmp_name'];
    $file_size2=$_FILES['header']['size'];
    $file_type2=$_FILES['header']['type'];

    $location2='../php/uploads/'.$file_name2;

    if(move_uploaded_file($file_temp2, $location2)){
        try{
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql2 = "UPDATE users SET header ='$location2' WHERE id = '$userid'";
            $conn->exec($sql2);
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    if(empty($firstname) || empty($lastname) || empty($username) || empty($email)
    || empty($password) || empty($reppass) )
    {
        header("Location: ../php/signup.php?error=emptyfild");
        exit();
    }

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../php/signup.php?error=brokenemail");
        exit();
    }

    elseif(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
        header("Location: ../php/signup.php?error=usernamebroken");
        exit();
    }

    elseif($password !== $reppass){
        header("Location: ../php/signup.php?error=passwordcheckwrong");
        exit();
    }

    else{
        $sql = "SELECT * FROM users WHERE email = :email";
        $sql_user = "SELECT username FROM users WHERE username = :username";
        $sql_email = "SELECT email FROM users WHERE email = :email";

        if(!$stmt = $conn->prepare($sql)){
            header("Location: ../php/signup.php?error=databaseconnectionfail");
            exit();
        }
        else{
            $stmt = $conn->prepare($sql_user);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $numberOfresults = $stmt->rowCount();

            if($numberOfresults > 0){
                header("Location: ../php/signup.php?error=usernametaken");
                exit();
            }
            $stmt = $conn->prepare($sql_email);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $numberOfresults = $stmt->rowCount();

            if($numberOfresults > 0){
                header("Location: ../php/signup.php?error=emialalreadyused");
                exit();
            }
            if($numberOfresults == 0){
                $sql = "INSERT INTO users (firstname, lastname, username, header_name, name, header, pfp, email, password) 
                VALUES (:firstname, :lastname, :username, :header_name, :name, :header, :pfp,:email, :hashed)";

                if(!$stmt = $conn->prepare($sql)){
                    header("Location: ../php/signup.php?error=databaseconnectionfail");
                    exit();
                }
                else{
                //hashes the password
                $hashed = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':header_name', $file_name2);
                $stmt->bindParam(':name', $file_name);
                $stmt->bindParam(':header', $location2);
                $stmt->bindParam(':pfp', $location);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':hashed', $hashed);
                $stmt->execute();

                $sql = "SELECT * FROM users WHERE email = '$email'";
                $query_res = $conn->query($sql);
                $count= count($query_res->fetchAll());
                if($count > 0){
                    $sql = "SELECT date FROM users WHERE email = '$email'";
                    $sth = $conn->query($sql);
                    $date = $sth->fetchColumn();
                }

                header("Location: ../php/signup.php?signup=success");
                header("Location: ../index.php");
                exit();
                }
            }
        }
    }

}

?>