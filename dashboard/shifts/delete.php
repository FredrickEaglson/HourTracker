
<?php
include $_SERVER['DOCUMENT_ROOT']."/auth/session.php";
include $_SERVER['DOCUMENT_ROOT']."/auth/admin/shifts/deletefunction.php";

$shift_id = $_GET['shift_id'];

deleteShift($shift_id);

header("Location: index.php");

?>
