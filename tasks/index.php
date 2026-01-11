<?php
use App\Types;

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$user = "cronjobs";
if (isset($_GET['taskhandler'])) {
    $user = $_GET['taskhandler'];
}

$sql = $con->prepare("SELECT * FROM `tasks` WHERE completed=0");
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows == 0) {
    echo "NO TASKS";
} else {
    foreach ($result as $task) {
        if (strtotime($task['effect_date']>time())){
            echo "task is in the future";
            break;
        }
        switch ($task['type']) {
            case "pp":
                task_payperiod($task);
            default: 
                echo "INVALID TASK TYPE";
                break;
        }
    }
}
/*

*/

function task_payperiod($task) {

    echo "----STARTING PAY PERIOD TASK: ". $task['taskid']." ----";

    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    if ($task['type'] != "pp") {
        echo "not payperiod task, error occured on task: ".$task['taskid'];

    }

    $data = json_decode($task['data']);
    
    $startdate = date("Y-m-d",strtotime($data['startdate']));
    $enddate = date("Y-m-d",strtotime($data['enddate']));
    $rate = $data['rate'];
    $userid = $data['userid'];
    $name = $data['name'];
    $hours = 0;

    $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=? && `startdate`=? && `enddate`=? && `name`=?");
    $sql->bind_param("sss", $userid, $startdate, $enddate,$name);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows == 0) {
        echo "payperiod does not exist, crating it";

        $sql = $con->prepare("INSERT INTO `payperiods` (`name`, `startdate`, `enddate`, `rate`, `userid`,shift_ids,shifts,`money`,) VALUES (?, ?, ?, ?, ?,'',0,0)");
        $sql->bind_param("sssdss", $name, $startdate, $enddate, $rate, $userid);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows == 0) {
            echo "Error";
            echo $result->error;
            echo $sql->error;
        } else {
            $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=? && `startdate`=? && `enddate`=? && `name`=?");
            $sql->bind_param("sss", $userid, $startdate, $enddate,$name);
            $sql->execute();

            $result = $sql->get_result();

            if ($result->num_rows==0) {
                echo "Error";
                echo $result->error;
                echo $sql->error;
            } else {
                echo "payperiod created";
                echo "ppid: ". $result->fetch_assoc()['ppid'];
            }

        }
    }
    $sqlupdate = $con->prepare("UPDATE `tasks` SET `completed`=1 WHERE `taskid`=?");
    $sqlupdate->bind_param("s", $task['taskid']);
    $sqlupdate->execute();

    if ($task['onetime']==false){
/*
# Reoccur data will be as follows

{
    "reoccur":true //if not included, the reoccur will fail,
    
    "enddate":"2026-02-28",
    "interval":14 //in days,
    "name":"Pay Period Auto" //will show in tasks db,
    "data":{
        "copyLast":true //if true, the data from the task that caused the creation will occur
    }
}


*/


        $reoccurData = json_decode($task['reoccurData']);
        if ($reoccurData['reoccur']!=true){
            echo "reoccur is not true";
            return;
        }

        $effectiveDate = strtotime($task['effective_date'])+(60*60*24*($reoccurData['interval']-1));
        $insertData = $data;
        $userid = $_task['userid'];
        $oneTime = false;
        $type="pp";
        $end_date = date("Y-m-d",strtotime($task['end_date']));
        $status = "Data cloned from task:t{".$task['taskid']."}";
        
        $sqlinsert = $con->prepare("INSERT INTO `tasks` (`userid`, `type`, `status`, `data`, `effective_date`, `end_date`, `onetime`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sqlinsert->bind_param("ssssss", $userid, $type, $status, json_encode($insertData), $effectiveDate, $end_date, $oneTime);
        $sqlinsert->execute();
        $sql3 = $con->prepare("SELECT * FROM `tasks` ORDER BY `Creation_Date` DESC LIMIT 1;");
        $sql3->execute();
        $result2 = $sql3->get_result();
        echo "inserted new task with id:t{".$result2->fetch_assoc()['taskid']."}";
        

        
    }





    echo "----TASK COMPLETED----";
    return;
}
