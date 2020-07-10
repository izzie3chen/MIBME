<?php
ob_start(); //turns on output buffering
session_start();
$timezone=date_default_timezone_set("America/Los_Angeles");
// $con = mysqli_connect("localhost", "root", "", "social"); //development mode
$con=mysqli_connect("us-cdbr-iron-east-01.cleardb.net","bfda1f90a0ada4","e34eb4be","heroku_70573b64eb0ea17"); //production mode
if(mysqli_connect_errno()) {
    echo "Failed to connect: ".mysqli_connect_errno();
}
