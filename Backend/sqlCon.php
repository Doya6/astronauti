<?php

$username="techfides";
$password="tEch!fIdEs!001";
$host="localhost";
$database="astronauts";

$connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// if (!$connect) {
// echo (" NEnapojeno na sql");
//   die("Connection failed: " . mysqli_connect_error());
// } else echo (" --- napojeno na sql databazi '" . $database . "' --- ");

?>