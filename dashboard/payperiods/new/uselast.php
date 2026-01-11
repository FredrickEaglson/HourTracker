<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

$userid = $_SESSION['userid'];

$sqlacc = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$sqlacc->bind_param("s", $userid);
$sqlacc->execute();
$acc = $sqlacc->get_result();
$acc = $acc->fetch_assoc();
$pplength = $acc['pplength'];

$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=? ORDER BY `enddate` DESC LIMIT 1");
$sql->bind_param("s", $userid);
$sql->execute();
$result = $sql->get_result()->fetch_assoc();
$lastend = $result['enddate'];

$new['startdate'] = date("Y-m-d",strtotime($lastend)+(60*60*24));
$new['enddate'] = date("Y-m-d",strtotime($lastend)+($pplength*60*60*24));
$new['userid'] = $userid;
$new['name'] = $result['name'];
$new['rate'] = $result['rate'];

$sqlinsert = $con->prepare("INSERT INTO `payperiods` (`name`, `startdate`, `enddate`, `rate`, `userid`) VALUES (?, ?, ?, ?, ?)");
$sqlinsert->bind_param("sssds", $new['name'], $new['startdate'], $new['enddate'], $new['rate'], $new['userid']);
$sqlinsert->execute();

if ($sqlinsert->affected_rows > 0){
    echo "success";
    header("Location: ".$_SERVER['SRVROOT']."/dashboard/payperiods");
}
 ?>