<?php

session_start();

$defaultrate = 0.0;
$totaltime = 0;
$totalbt = 0;
$ppid = '';
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

$id = $_GET['id'];

$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
$sql->bind_param("ss", $id, $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
$ppid = $row['ppid'];
$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);

if ($row['userid'] != $_SESSION['userid']) {
    header("Location: ../paychecks/index.php");
}

$sql2 = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
$sql2->bind_param("ss", $row['ppid'], $_SESSION['userid']);
$sql2->execute();
$ppinfo = $sql2->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    exit(10);
}


$sql3 = $con->prepare("SELECT AVG(`taxrate`) AS taxrate, AVG(`realrate`) AS realrate FROM paychecks WHERE `userid`=?");
$sql3->bind_param("s", $_SESSION['userid']);

$sql3->execute();
$result3 = $sql3->get_result()->fetch_assoc();
$estTaxRate = $result3['taxrate'];
$estRealRate = $result3['realrate'];

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
                <h2 class="text-center text-2xl mb-5">Preview Pay Check</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <form class="w-full max-w-md" method="post">

                        <div class="grid grid-cols-3 grid-rows-2 gap-4">

                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="startdate">Start Date</label>
                                <input type="date" class="max-w-full" name="startdate" required value="<?php echo date("Y-m-d", strtotime($ppinfo['startdate'])); ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="enddate">End Date</label>
                                <input type="date" class="max-w-full" name="enddate" required value="<?php echo date("Y-m-d", strtotime($ppinfo['enddate'])); ?>">
                            </div>
                            <?php if (isset($_GET['showsens']) && $_GET['showsens'] == 1) : ?>
                                <div class="p-2 bg-red-200 rounded border border-black border-solid">
                                    <label for="userid">User ID</label>
                                    <input type="text" class="max-w-full" name="userid" readonly value="<?php echo $row['userid']; ?>">
                                </div>
                                <div class="p-2 bg-red-200 rounded border border-black border-solid">
                                    <label for="periodID">Pay Period ID</label>
                                    <input type="text" class="max-w-full" name="payperiodID" readonly value="<?php echo $row['ppid']; ?>">
                                </div>
                                <div class="p-2 bg-red-200 rounded border border-black border-solid">
                                    <label for="shifts">Pay Check ID</label>
                                    <input type="text" class="max-w-full" name="pcid" readonly value="<?php echo $row['pcid']; ?>">
                                </div>
                            <?php endif; ?>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="payrate">Pay Rate</label>
                                <input type="number" class="max-w-full inline border border-black border-solid" step="0.01" name="payrate" value="<?php echo $row['rate']; ?>">
                            </div>

                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Hours</label>
                                <input type="number" class="max-w-full" name="hours" value="<?php echo floor($row['hours']) ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Minutes</label>
                                <input type="number" class="max-w-full" name="minutes" value="<?php echo formatmins(floor(($row['hours'] - floor($row['hours'])) * 60)) ?>">
                            </div>



                            <div class="p-2 bg-green-200 rounded border border-black border-solid">
                                <label for="periodID">Hourly</label>
                                <input type="text" class="max-w-full" name="hourly" readonly value="<?php echo $formatter->formatCurrency($row['hours'] * $row['rate'], "USD"); ?>">
                            </div>


                            <div class="p-2 bg-green-200 rounded border border-black border-solid">
                                <label for="periodID">Est. Tax Rate</label>
                                <input type="number" step="0.000001" class="max-w-full" name="taxes" value="<?php echo round($estTaxRate, 6); ?>">
                            </div>
                            <div class="p-2 bg-green-200 rounded border border-black border-solid">
                                <label for="periodID">Est. Real Rate</label>
                                <input type="text" class="max-w-full" name="taxes" value="<?php echo $formatter->formatCurrency($estRealRate, "USD"); ?>">
                            </div>
                            <div class="p-2 bg-green-200 rounded border border-black border-solid">
                                <label for="periodID">Net</label>
                                <input type="text" class="max-w-full" name="net" readonly value="<?php echo $formatter->formatCurrency($row['hours'] * $estRealRate, "USD"); ?>">
                            </div>







                        </div>

                        <div class="flex flex-col justify-center items-center p-3 m-4">
                            <div class="mb-5">
                                <ul>
                                    <?php
                                    $sql = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? AND `ppid`=? ORDER BY `date` ASC");
                                    $sql->bind_param("ss", $_SESSION['userid'], $row['ppid']);
                                    $sql->execute();
                                    $result2 = $sql->get_result();
                                    if ($result2->num_rows > 0) {
                                        foreach ($result2 as $row2) : ?>
                                            <a href="#">
                                                <li>
                                                    <div class="flex flex-col justify-center items-center p-3 m-4 border  text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]
                                                        <?php
                                                        if ($row2['worked'] == FALSE) {
                                                            echo 'border-yellow-600';
                                                        } else {
                                                            echo 'border-black';
                                                        }
                                                        ?>
                                                    ">

                                                        <span class="flex flex-row w-full justify-between ">
                                                            <h3
                                                                class="flex flex-row mr-6 text-lg payperiodname"
                                                                aria-label="pay period name">
                                                                <?= date('D, M d, Y', strtotime($row2['date'])) ?>

                                                            </h3>
                                                            <span class="flex flex-row align-bottom text-lg">
                                                                <span class="flex flex-row mr-2 text-lg "> <?= floor($row2['hours']) . ':' . formatmins(floor(($row2['hours'] - floor($row2['hours'])) * 60)) ?>
                                                                </span>
                                                                <span class="flex flex-row text-red-700 text-lg "><?= $formatter->formatCurrency($row2['rate'], "USD") ?>
                                                                </span>
                                                                <span class="flex flex-row ml-2 text-lg "> <?= $formatter->formatCurrency($row2['rate'] * $row2['hours'], "USD"); ?>
                                                                </span>
                                                            </span>
                                                        </span>

                                                    </div>
                                                </li>
                                            </a>

                                    <?php endforeach;
                                    } ?>
                                </ul>
                            </div>
                        </div>


                        </ul>
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