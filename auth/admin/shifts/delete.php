<?php

function deleteShift($shift_id)
{
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbconn.php";

    $shift_id = mysqli_real_escape_string($conn, $shift_id);

    $sql = $conn->prepare("SELECT * FROM shifts WHERE shift_id = ?");
    $sql->bind_param("s", $shift_id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $sql = $conn->prepare("UPDATE shifts SET userid = ? status = ? WHERE shift_id = ?");
        $shift_id = $result->fetch_assoc()["uuid"];
        $stutus = "deleted:true;approved:false;oldowner:" . $result->fetch_assoc()["userid"];
        $sql->bind_param("sss", "45eb3dc0-f9a7-11f0-9685-a029190ac76c", $stutus, $shift_id);
        $sql->execute();

        $sql = $conn->prepare("INSERT INTO admin_tasks (`type`,`notes`) VALUES (?,?)");
        $notes = "SHIFT_DELETED\ns{".$shift_id."}";
        $sql->bind_param("ss", "delete",$notes);
        $sql->execute();

        

    }
}
