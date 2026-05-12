<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/constants.php";

$id = $_GET['id'];

$acc = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$acc->bind_param("s", $_SESSION['userid']);
$acc->execute();
$result = $acc->get_result();
if ($result->num_rows == 1) {
    $acc = $result->fetch_assoc();
    if (!in_array($acc['account_type'], PRIVLEDGED_ROLES)) {
        header("Location: ../dashboad?e=unprivf");
    }
}


function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

$newID = gen_uuid();

$con->query("START TRANSACTION");

$ppsql = $con->prepare("UPDATE `payperiods` SET `ppid`=? WHERE `ppid`=?");
$ppsql->bind_param("ss", $newID, $id);
$ppsql->execute();
$res = $ppsql->get_result();
if ($ppsql->affected_rows>0) {
    echo "rows updated";
}

$shiftsql = $con->prepare("UPDATE `shifts` SET `ppid`=? WHERE `ppid`=?");
$shiftsql->bind_param("ss", $newID, $id);
$shiftsql->execute();
$res = $shiftsql->get_result();
if ($shiftsql->affected_rows>0) {
    echo "rows updated";
}


$con->query("COMMIT");