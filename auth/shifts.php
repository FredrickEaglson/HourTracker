<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $ppid = $_GET['ppid'];
    $userid = $_SESSION['userid'];
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    $sql = $con->prepare("SELECT `date`, `rate` ,`hours` FROM `shifts` WHERE `userid`=? AND `ppid`=? ORDER BY `date` ASC");
    $sql->bind_param("ss", $userid, $ppid);
    $sql->execute();
    $result = $sql->get_result();

    header("Content-type: text/xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<shifts>';
    while ($row = $result->fetch_assoc()) {
        echo '<shift date="' . $row['date'] . '" rate="' . $row['rate'] . '" hours="' . $row['hours'] . '" minutes="' . round(floor($row['hours'] - floor($row['hours'])) * 60,-2) . '" />';
    }

    echo '</shifts>';
}