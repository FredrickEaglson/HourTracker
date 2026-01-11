<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

if (!isset($_SESSION['expire']) || time() > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}


?>