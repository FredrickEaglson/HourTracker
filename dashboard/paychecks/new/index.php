<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
$defaultrate = 0.0;

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$sql = $con->prepare("SELECT `defaultrate` FROM `accounts` WHERE `userid`=?");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
$defaultrate = $row['defaultrate'];


$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);

if (!isset($_GET['ppid'])) {
    header("Location: ./manual.php");
    exit();
}
$ppid = $_GET['ppid'];
$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
$sql->bind_param("ss", $ppid, $_SESSION['userid']);
$ppresult = $sql->get_result();
if ($ppresult->num_rows == 0) {
    header("Location: ./manual.php?=ppnfound");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>

    <link rel="stylesheet" href="../../../app/styles/global.css">
</head>

<body class="w-screen">
    <?php if (isset($_GET['e'])){

        $errModalCode = ERRORS[$_GET['e']];
        include $_SERVER['DOCUMENT_ROOT'] . "/components/error/modal.php";

     } ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>
    <main>
        
    </main>


    
</body>

</html>