<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/app/constants.php";
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

function notification($user, $type, $data) {
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    $con->query("START TRANSACTION");
    $sql = $con->prepare("INSERT INTO `notifications` (`userid`, `type`, `data`) VALUES (?, ?, ?)");



}

?>