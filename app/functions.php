<?php

function getcurpp(): string
{
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    $date = date("Y-m-d");
    $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `startdate` <= ? AND `enddate` >= ?");
    $sql->bind_param("ss", $date, $date);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows == 1) {
        return $result->fetch_assoc()['ppid'];
    } else {
        return 'NULL';
    }
}

function validateDate($date, $format = 'F d, Y')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && strtolower($d->format($format)) === strtolower($date);
}

function statusstring($statusarr): string {
    $out ="";
    foreach ($statusarr as $key => $value) {
        $out.=$key.":".$value.";";
        
    }
    return $out;
}
function submit_to_sql($filecontents, $ppid)
{
    echo date("j-M-y");
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";

    $hours;
    $date;
    $clockin;
    $clockout;
    $rate = $_SESSION['defaultrate'] ?? 0.0;

    $rows = explode("\n", str_replace("\"", "", $filecontents));
    foreach ($rows as $row) {
        echo $row . "<br>";
    }
    $statusarr = array();
    $statusarr['pastowner']=$_SESSION['userid'];
    $statusarr['status']='deleted';
    $statusarr['re']='imported file on'.date("j-M-y");
    $statusarr['timestamp']=date("j-M-y H:i:s");
    $status = statusstring($statusarr);

    $sql = $con->prepare("UPDATE `shifts` SET `userid`='45eb3dc0-f9a7-11f0-9685-a029190ac76c' and `status`=? WHERE `userid`=? and `ppid`=?");


    foreach ($rows as $row) {
        $row = explode(",", $row);

        if ($row[0] == $_SESSION['csvname']) {
        } else if ($row[0] == "Date") {
            $date = 0;
            foreach ($row as $i => $cell) {
                switch (strtolower($cell)) {
                    case "date":
                        $date = $i;
                    case "clock in":
                        $clockin = $i;
                    case "clock out":
                        $clockout = $i;
                    case "hours":
                        $hours = $i;
                }
            }
            echo "date: " . $date . "<br>";
            echo "clockin: " . $clockin . "<br>";
            echo "clockout: " . $clockout . "<br>";
            echo "hours: " . $hours . "<br>";
        } else if (validateDate($row[0])) {
            echo "=================<br>";
            echo "row: " . implode(",", $row) . "<br>";
            echo "date: " . $row[$date] . "<br>";
            $data['date'] = $row[$date];
            echo "clock in: " . $row[$clockin] . "<br>";
            $data['clockin'] = $row[$clockin];
            echo "clock out: " . $row[$clockout] . "<br>";
            $data['clockout'] = $row[$clockout];
            echo "hours: " . $row[$hours] . "<br>";
            $data['hours'] = $row[$hours];



            echo "SELECT * FROM `shifts` WHERE `userid`=" . $_SESSION['userid'] . " and `date`=" . $data['date'] . "<br>";
            $ddate = date("Y-m-d", strtotime($data['date']));
            $sql = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? and `date`=?");
            $sql->bind_param("ss", $_SESSION['userid'], $ddate);
            $sql->execute();
            $result = $sql->get_result();

            $go;

            
                echo "shift id: null<br>";
                echo "creating shift<br>";

                $dminutes = floor($data['hours'] * 60);
                $udate = date("Y-m-d", strtotime($data['date']));
                echo $udate."<br>";

                $dclockin = date("Y-m-d G:i:s", strtotime(date("Y-m-d", strtotime($data['date'])) . " " . $data['clockin']));
                $dclockout = date("Y-m-d G:i:s", strtotime(date("Y-m-d", strtotime($data['date'])) . " " . $data['clockout']));
                $sql = $con->prepare("INSERT INTO `shifts` (`userid`,`ppid`,`clockin`,`clockout`,`minutes`,`rate`,`date`) VALUES (?,?,?,?,?,?,?)");
                $sql->bind_param("sssssss", $_SESSION['userid'], $ppid, $dclockin, $dclockout, $dminutes, $rate,$udate);
                $sql->execute();
                if ($sql->errno) {
                    $sql = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? AND `date`=?");

                    $sql->bind_param("ss", $_SESSION['userid'], $ddate);
                    $sql->execute();
                
            }
        } else {
            echo "=================<br>";
        }
    }
}
