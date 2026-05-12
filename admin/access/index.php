<?php 
include $_SERVER['DOCUMENT_ROOT']."/auth/session.php";
include $_SERVER['DOCUMENT_ROOT']."/auth/dbcon.php";

$type = $_GET['type'];
$id = $_GET['id'];

switch ($type) {
    case "shift":
        include $_SERVER['DOCUMENT_ROOT']."/admin/access/shifts/index.php";
        break;
    case "payperiod":
        include $_SERVER['DOCUMENT_ROOT']."/admin/access/payperiods/index.php";
        break;
    default:
        echo "Invalid Type";
        break;
}