<?php
session_start();
ob_start();
$hasDB = false;
$server = 'localhost';
$user = 'cediva';
$pass = 'cediva123';
$db = 'CEDIVA';
$mySQL = new mysqli($server,$user,$pass,$db);
if ($mySQL->connect_error)
{
    die('Connect Error (' . $mySQL->connect_errno . ') '. $mySQL->connect_error);
}
?>
