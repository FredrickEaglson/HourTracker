<?php

function deleteShift($shift_id)
{
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

    $shift_id = mysqli_real_escape_string($con, $shift_id);

    $sql = $con->prepare("SELECT * FROM `shifts` WHERE uuid = ?");
    $sql->bind_param("s", $shift_id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $d = $result->fetch_assoc();
        $sql = $con->prepare("UPDATE `shifts` SET userid = ?, `status` = ? WHERE `uuid` = ?");
        $shift_id = $d["uuid"];
        $stutus = "deleted:true;approved:false;oldowner:" . $d["userid"];
        $admin = "45eb3dc0-f9a7-11f0-9685-a029190ac76c";
        $sql->bind_param("sss", $admin, $stutus, $shift_id);
        $sql->execute();

        $sql = $con->prepare("INSERT INTO `admin_tasks` (`type`,`notes`) VALUES (?,?)");
        $notes = "SHIFT_DELETED\ns{".$shift_id."}";
        $type = "delete";
        $sql->bind_param("ss",$type ,$notes);
        $sql->execute();

        

    }
}
