<?php

//start session
session_start();

$server = "localhost";
$user = "root";
$password = "";
$database = "wisteria_db";
//sets up the connection to the database
$link = mysqli_connect($server, $user, $password, $database);

?>
