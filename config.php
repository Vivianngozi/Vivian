<?php
//simple application
//prepared statemrnts

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
$connect = new mysqli(
    $servername, $username, $password, $dbname
);

if ($connect->connect_error){
    die("connection failed".$connect->connect_error);
}
?>


