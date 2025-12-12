<?php

session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $shiftid = $_GET['id'];
    $payperiodid = $_GET['ppid'];

    $sql = $con->prepare("UPDATE `shifts` SET `ppid`=? WHERE `uuid`=? AND `userid`=?");
    $sql->bind_param("sss", $payperiodid, $shiftid, $_SESSION['userid']);
    $sql->execute();
    $result = $sql->get_result();

    if ($sql->errno) {
        echo "great";
    }

    $sql = $con->prepare("SELECT * FROM `shifts` where `uuid`=? AND `ppid`=? AND `userid`=?");
    $sql->bind_param("sss", $shiftid, $payperiodid, $userid);
    $sql->execute();
    $result = $sql->get_result();

    $hours = $rate = $total = 0.0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $hours = $row['hours'];
        $rate = $row['rate'];
        $total = $rate * $hours;
    }

    $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? AND `userid`=?");
    $sql->bind_param("ss", $payperiodid, $_SESSION['userid']);
    $sql->execute();
    $result = $sql->get_result();


    $shifts = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hours = $row['hours'] + $hours;
        $shifts = $row['shifts'] + 1;
        $shiftids = $row['shift_ids'] . ',' . $shiftid;

        $sql = $con->prepare("UPDATE `payperiods` SET `shifts`=? AND `shift_ids`=? WHERE `ppid`=? AND `userid`=?");
        $sql->bind_param("isss", $shifts, $shiftids, $payperiodid, $userid);

        $sql->execute();
        $result = $sql->get_result();
        echo $sql->error;
        echo $result;
        header("Location: /dashboard/payperiods/update.php?id=" . $payperiodid . "&r=./edit.php?id=" . $payperiodid);
    }
}
