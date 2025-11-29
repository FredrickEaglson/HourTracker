<?php
session_start();
$defaultrate = 0.0;

include "../..//auth/dbcon.php";

$id = $_GET['id'];

$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
$sql->bind_param("ss", $id, $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>

    
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
                                <input type="text" class="max-w-full border border-black" name="name" value="<?php echo $row['name']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid" >
                                <label for="startdate">Start Date</label>
                                <input type="date" class="max-w-full" name="startdate" required value="<?php echo date("Y-m-d", strtotime($row['startdate'])); ?>" >
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="enddate">End Date</label>
                                <input type="date" class="max-w-full" name="enddate" required value="<?php echo date("Y-m-d", strtotime($row['enddate'])); ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="payrate">Pay Rate</label>
                                <input type="number" class="max-w-full border border-black border-solid" step="0.01" name="payrate" value="<?php echo $row['rate']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">User ID</label>
                                <input type="text" class="max-w-full" name="userid" readonly value="<?php echo $row['userid']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Pay Period ID</label>
                                <input type="text" class="max-w-full" name="payperiodID" readonly value="<?php echo $row['ppid']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border1">
                                <button type="submit" class="w-full">Insert</button>
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