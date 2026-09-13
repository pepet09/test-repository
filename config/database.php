<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "proposed_dpwh_repository";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

date_default_timezone_set("Asia/Manila");
?>