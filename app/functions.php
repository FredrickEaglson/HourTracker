<?php

function getcurpp():string{
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    $date = date("Y-m-d");
    $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `startdate` <= ? AND `enddate` >= ?");
    $sql->bind_param("ss", $date, $date);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows ==1) {
        return $result->fetch_assoc()['ppid'];
    } else {
        return 'NULL';
    } 
}