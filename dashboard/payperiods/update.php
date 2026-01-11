<?php

include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

$totalmoney = $totalhours = 0.0;
$shiftids = '';
$shifts = 0;

$id = $_GET['id'];

$sql = $con->prepare("SELECT * FROM `shifts` WHERE `ppid`=? AND `userid`=?");
$sql->bind_param("ss", $id, $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();

while ($row = $result->fetch_assoc()) {
    echo $row['uuid'];
    $totalhours += $row['minutes']/60;
    $totalmoney += ($row['rate'] * $row['minutes']/60);
    $shiftids .= ',' . $row['uuid'];
    $shifts++;
}

$shiftids = substr($shiftids, 1);

$sql = $con->prepare("UPDATE `payperiods` SET `hours`=?, `shifts`=?, shift_ids=?, `money`=? WHERE `ppid`=?");
$sql->bind_param("disds", $totalhours, $shifts, $shiftids, $totalmoney, $id);
$sql->execute();
echo "<br>";
echo $_GET['r'];    
header("Location: " . $_GET['r']); 