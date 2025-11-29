<?php 
session_start();

if (isset($_SESSION['user']) && $_SESSION['loggedin'] == true) {
    header("Location: dashboard/index.php");
} 

switch ($_GET['r']) {
    case 'reg':
        include "./pages/register.php";
        
        break;
    default:
        header("Location: login.php");
}