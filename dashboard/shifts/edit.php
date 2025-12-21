<?php
session_start();
$defaultrate = 0.0;
$totaltime = 0;
$totalbt = 0;
$ppid = '';
include "../..//auth/dbcon.php";

$id = $_GET['id'];

$sql = $con->prepare("SELECT * FROM `shifts` WHERE `uuid`=? && `userid`=?");
$sql->bind_param("ss", $id, $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
$ppid = $row['ppid'];
$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);




if ($row['userid'] != $_SESSION['userid']) {
    header("Location: ../payperiods/index.php");
    exit();
}


function formatmins($mins)
{
    $mins = floor($mins);

    if ($mins < 10) {
        return "0" . $mins;
    } else {
        return $mins;
    }
}

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
                <h2 class="text-center text-2xl mb-5">Edit Pay Period</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <form class="w-full max-w-md" method="post">

                        <div class="grid grid-cols-3 grid-rows-2 gap-4">
                            
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="startdate">Date</label>
                                <input type="date" class="max-w-full" name="startdate" required value="<?php echo date("Y-m-d", strtotime($row['date'])); ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="enddate">Clock In</label>
                                <input type="time" class="max-w-full" name="enddate" required value="<?php echo date("G:i", strtotime($row['clockin'])); ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="enddate">Clock Out</label>
                                <input type="time" class="max-w-full" name="enddate" required value="<?php echo date("G:i", strtotime($row['clockout'])); ?>">
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
                            
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Total Time</label>
                                <input type="text" class="max-w-full" name="hours" readonly value="<?php echo floor($row['hours']) . ':' . formatmins(floor(($row['hours'] - floor($row['hours'])) * 60)) ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Before Tax</label>
                                <input type="text" class="max-w-full" name="" readonly value="<?php echo $formatter->formatCurrency(($row['money'] ?? $row['rate'] * $row['hours']), "USD"); ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border1 h-full">
                                <button type="submit" class="w-full h-full">Update</button>
                            </div>
                            <div class="p-2  rounded border1 h-full text-center bg-red-100">
                                <a class="w-full h-full text-center text-red-700 " href="./delete.php?a=0&id=<?php echo $row['ppid'] . "&r=index.php"; ?>">Delete Shift</a>
                            </div>
                        </div>


                    </form>
                </div>

            </div>
            </div>
        </section>
    </main>


    <script>

    </script>
</body>

</html>