<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";

if (!isset($_SESSION['user']) && $_SESSION['loggedin'] != true) {
    header("Location: dashboard/index.php");
} 

switch ($_GET['r']) {
    case 'reg':
        header("Location: pages/register.php");
        
        break;
    default:
        header("Location: /");
}

