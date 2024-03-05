<?php 
$user = "root";
$password = "";
$server = "localhost";
$db = "grsedb";

$con = mysqli_connect($server, $user, $password, $db);
if (!$con) {
    die(mysqli_error($con));
}
?>