<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
if ($_GET['e'] == 1) {
    $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
    $sql->bind_param("ss", $id, $_SESSION['userid']);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $shiftids = explode(',', $row['shift_ids']);

        foreach($shiftids as $shiftid) {
            $sql = $con->prepare("UPDATE `shifts` SET `ppid`=? WHERE `uuid`=? AND `uuid`=?");
            $sql->bind_param("sss", $id, $shiftid, $shiftid);
            $sql->execute();


        }
    }





    
}

$totalhours = 0;
$shifts = 0;
$shiftids = '';

$id = $_GET['id'];
$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
$sql->bind_param("ss", $id, $_SESSION['userid']);
$sql->execute();

$result = $sql->get_result();
echo "<p>" . $result->num_rows . "</p>";
echo "<h1>" . $id . "</h1>";

$sql = $con->prepare("SELECT * FROM `shifts` WHERE `ppid`=? && `userid`=?");
$sql->bind_param("ss", $id, $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
echo "<h2>" . $result->num_rows . "</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['uuid'] . ":" . $row['hours'] . ":" . $row['rate'] . "</p>";
    $totalhours += $row['hours'];
    $shifts += 1;
    if ($shifts == 1) {
        $shiftids = $row['uuid'];
    } else {
        $shiftids .= ',' . $row['uuid'];
    }
}
echo "<p>*****************************************</p>";
echo "<p>" . $totalhours . " totalhours</p>";
echo "<p>" . $shifts . " shifts</p>";
echo "<p>" . $shiftids . " shiftids</p>";
$sql = $con->prepare("UPDATE `payperiods` SET `hours`=?, `shifts`=?,shift_ids=? WHERE `ppid`=?");
$sql->bind_param("diss", $totalhours, $shifts, $shiftids, $id);
$sql->execute();

header("Location: " . $_GET['r']);
