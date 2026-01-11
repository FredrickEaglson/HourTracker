<?php

include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";

$sqlverify = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$sqlverify->bind_param("s", $_SESSION['userid']);
$sqlverify->execute();
$verifyr = $sqlverify->get_result();

if ($verifyr->num_rows == 0) {
    header("Location: ../payperiods/index.php");
} else {
    $verifyrq = $verifyr->fetch_assoc();
    $accType = $verifyrq['account_type'];
    if ($accType != "admin") {
        echo "You are not an admin";
        exit();
    }
}

$sql = $con->prepare("SELECT * FROM `shifts` WHERE `hours` IS NOT NULL");
$sql->execute();
$sqlr = $sql->get_result();

foreach ($sqlr as $row) {
    $sql = $con->prepare("UPDATE `shifts` SET `minutes`=?, `hours`=NULL WHERE `uuid`=?");
    $minutes = $row['hours']*60;
    $sql->bind_param("is", $minutes, $row['uuid']);
    $sql->execute();
    echo "Updated: s{".$row['uuid']."}<br>";
}