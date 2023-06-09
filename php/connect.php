<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";

//Connecting to the database using PDO
$dsn = "mysql:host=" . $servername . ";dbname=webapps".";charset=utf8;";
try{
    $conn = new PDO ($dsn,$username, $password);
} 
catch(PDOException $e){
    echo "Failed to Connect: " . $e->getMessage(). "<br/>";
    die();
}