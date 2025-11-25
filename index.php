<?php 
session_start();
if (isset($_SESSION['loggedin'])&&$_SESSION['loggedin']==true)
{
    header("Location: app/dashboard.php");
} else {
    header("Location: auth/login.php");
}