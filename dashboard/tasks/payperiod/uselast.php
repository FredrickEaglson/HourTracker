<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
$defaultrate = 0.0;

include $_SERVER['DOCUMENT_ROOT']."/auth/dbcon.php";
$sql = $con->prepare("SELECT `defaultrate` FROM `accounts` WHERE `userid`=?");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
$defaultrate = $row['defaultrate'];



$sqlacc = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$sqlacc->bind_param("s", $_SESSION['userid']);
$sqlacc->execute();
$acc = $sqlacc->get_result();
$acc = $acc->fetch_assoc();
$pplength = $acc['pplength'];

$sql1 = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=? ORDER BY `enddate` DESC LIMIT 1");
$sql1->bind_param("s", $_SESSION['userid']);
$sql1->execute();
$result = $sql1->get_result()->fetch_assoc();
$lastend = $result['enddate'];

$new['startdate'] = date("Y-m-d",strtotime($lastend)+(60*60*24));
$new['enddate'] = date("Y-m-d",strtotime($lastend)+($pplength*60*60*24));
$new['userid'] = $_SESSION['userid'];
$new['name'] = $result['name'];
$new['rate'] = $result['rate'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>

    <link rel="stylesheet" href="../../../app/styles/global.css">
</head>

<body class="w-screen">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>
    <main>
        <section class="place-content-center">
            <div class="flex flex-col justify-center items-center p-3 m-4 border-solid rounded-4xl  border-4 border-black shadow-2xl">
                <h2 class="text-center text-2xl mb-5">New Pay Period</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <form class="w-full max-w-md" method="post">

                        <div class="grid grid-cols-3 grid-rows-2 gap-4">
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="name">Name</label>
                                <input type="text" class="max-w-full border border-black" name="name" value="<?= $new['name'] ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="name">Payperiod Length</label>
                                <input type="text" class="max-w-full border border-black" name="pplen" value="<?= $pplength ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="startdate">Start Date (Based on last)</label>
                                <input type="date" class="max-w-full" name="startdate" required value="<?= $new['startdate'] ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="enddate">End Date (Based on last)</label>
                                <input type="date" class="max-w-full" name="enddate" required value="<?= $new['enddate'] ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="payrate">Pay Rate (Based on last)</label>
                                <input type="number" class="max-w-full border border-black border-solid" step="0.01" name="payrate" value="<?php echo $defaultrate ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">User ID (Based on last)</label>
                                <input type="text" class="max-w-full" name="userid" readonly value="<?php echo $_SESSION['userid']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">Auto Create new?</label>
                                <select name="createnew">
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">Auto </label>
                                <select name="createnew">
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>


                            <div class="p-2 bg-slate-200 rounded border1">
                                <button type="submit" class="w-full">Create Task</button>
                            </div>
                            
                        </div>

                        <div class="flex flex-col justify-center items-center p-3 m-4">
                            <form method="POST">
                                <table class="border1 border-collapse gap-2">
                                    <tr class="border1 border-collapse">
                                        <th>Shift</th>
                                        <th>Date</th>
                                        <th>Hours</th>
                                        <th>Minutes</th>
                                        <th>Rate</th>
                                    </tr>


                                    <tbody id="tbodshifts">
                                        <?php

                                        include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
                                        $sql = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? AND `ppid`=NULL");
                                        $sql->bind_param("s", $_SESSION['userid']);
                                        $sql->execute();
                                        $result = $sql->get_result();

                                        if ($result->num_rows == 0 && sizeof($_SESSION['tempshifts']) == 1) {
                                            echo "<h2 class='text-center'>You have no shifts</h2>";
                                            echo "<input type='hidden' name='shifts[]' value=''>";
                                        }

                                        ?>
                                    </tbody>

                                </table>

                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>


    <script>

    </script>
</body>

</html>