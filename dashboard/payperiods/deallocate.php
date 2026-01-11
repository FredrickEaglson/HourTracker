<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    $sql = $con->prepare("SELECT * FROM `shifts` WHERE `uuid`=? && `userid`=?");
    $sql->bind_param("ss", $id, $_SESSION['userid']);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows == 0) {
        echo "<h1>No shifts found</h1>";
        echo "<a href=\"./\">Go Back</a>";
    } else {
        $row = $result->fetch_assoc();
        $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
        $sql->bind_param("ss", $row['ppid'], $_SESSION['userid']);
        $sql->execute();
        $result = $sql->get_result();
        $row2 = $result->fetch_assoc();
        $hours = $row2['hours'] - $row['hours'];
        $shifts = $row2['shifts'] - 1;
        $shiftids = $row2['shift_ids'];
        $shiftids = explode(',', $shiftids);
        $shiftids = array_diff($shiftids, [$id]);
        $shiftids = implode(',', $shiftids);
        $sql = $con->prepare("UPDATE `payperiods` SET `hours`=?, `shifts`=?,shift_ids=? WHERE `ppid`=? AND `userid`=?");
        $sql->bind_param("disss", $hours, $shifts, $shiftids, $row['ppid'], $_SESSION['userid']);
        $sql->execute();
        $sql = $con->prepare("UPDATE `shifts` SET `ppid` = NULL WHERE `uuid` = ? AND ppid=?");
        $sql->bind_param("ss", $id, $row['ppid']);
        $sql->execute();

        header("Location: " . $_GET['r']);
        
    }
}