<?php
// Database connection configuration
$aqell_host = "localhost";
$aqell_username = "root";
$aqell_password = "";
$aqell_database = "aqell_bk";

// Create connection
$aqell_conn = mysqli_connect($aqell_host, $aqell_username, $aqell_password, $aqell_database);

// Check connection
if (!$aqell_conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>