<?php
// Properties
$server = 'dashmash.ddns.net';
$user = '';
$pass = '';
$db = '';

//Create connection
$dbconnect = new mysqli($server, $user, $pass, $db);

mysqli_set_charset($dbconnect, 'utf-8');

//Check if connection was successfull
if ($dbconnect->connect_error) {
    echo '<div id="conn_error"><span>';
    die("Connection failed: " . '<strong>' . $dbconnect->connect_error . '</strong>');
    echo '</span></div>';
}