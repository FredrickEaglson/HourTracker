<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
$defaultrate = 0.0;

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$sql = $con->prepare("SELECT `defaultrate` FROM `accounts` WHERE `userid`=?");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
$defaultrate = $row['defaultrate'];


$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);


$ppresult;

$sql2 = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=?");
$sql2->bind_param("s", $_SESSION['userid']);
$sql2->execute();
$ppresult = $sql2->get_result();
$row1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ppid = $_POST['payperiod'];
    $hours = $_POST['hours'] + $_POST['minutes'] / 60;
    $tips = $_POST['tips'];
    $tax = $_POST['tax'];
    $deductions = $_POST['deductions'];
    $pretax = $_POST['pretax'];
    $posttax = $_POST['posttax'];

    $rate = $_POST['payrate'];

    $total = $hours * $rate + $_POST['othours'] * $_POST['otrate'] + $tips - $deductions - $tax;

    $sql3 = $con->prepare("INSERT INTO `paychecks` (`userid`, `ppid`, `hours`,  `tips`, `taxes`, `deductions`, `hourly`, `net`, `rate`,`othours`,`otrate`,`totalmoney`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)");
    $sql3->bind_param("ssdddddddddd", $_SESSION['userid'], $ppid, $hours, $tips, $tax, $deductions, $pretax, $posttax, $rate, $_POST['othours'], $_POST['otrate'], $total);
    $sql3->execute();
    header("Location: ../");
}







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
                <h2 class="text-center text-2xl mb-5">New Pay Check</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <form class="w-full max-w-md" method="post">
                        <div class="grid grid-cols-6 gap-4">
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid mb-4 col-span-5">
                                <select id="s_ppid" name="payperiod" class="w-full">
                                    <option value="">Select Pay Period</option>
                                    <?php foreach ($ppresult as $row1) : ?>

                                        <option
                                            value="<?php echo $row1['ppid']; ?>">
                                            <?= $row1['name'] . ' — ' . date('d/m/y', strtotime($row1['startdate'])) . ' - ' . date('d/m/y', strtotime($row1['enddate']));
                                            ?> </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid mb-4">
                                <button type="button" id="b_find">Find</button>

                            </div>
                        </div>

                        <div class="grid grid-cols-3 grid-rows-2 gap-4">


                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="payrate">Pay Rate</label>
                                <input type="number" class="max-w-full border border-black border-solid" step="0.01" name="payrate" value="<?php echo $defaultrate ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">User ID</label>
                                <input type="text" class="max-w-full" name="userid" readonly value="<?php echo $_SESSION['userid']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Pay Check ID</label>
                                <input type="text" class="max-w-full" name="payperiodID" readonly value="N/A">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Date</label>
                                <input type="date" id="date" class="max-w-full" name="date" required>
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Hours</label>
                                <input type="number" id="hours" class="max-w-full" name="hours" step="0.000001" required value="0">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Minutes</label>
                                <input type="number" id="minutes" class="max-w-full" name="minutes" step="1" required value="0">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Overtime Hours</label>
                                <input type="number" id="othours" class="max-w-full" name="othours" step="0.000001" required value="0">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Overtime Rate</label>
                                <input type="number" id="otrate" class="max-w-full" name="otrate" step="0.000001" required value="0">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Tips</label>
                                <input type="number" class="max-w-full" name="tips" step="0.01" required value="0">
                            </div>

                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Tax</label>
                                <input type="number" class="max-w-full" name="tax" step="0.01" required value="0">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Deductions</label>
                                <input type="number" class="max-w-full" name="deductions" step="0.01" required value="0">
                            </div>

                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Pretax Total</label>
                                <input type="number" class="max-w-full" name="pretax" step="0.01" required value="0">
                            </div>

                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Post Tax Total</label>
                                <input type="number" class="max-w-full" name="posttax" step="0.01" required value="0">
                            </div>

                            <div class="p-2 bg-slate-200 col-span-full rounded border1 text-center text-2xl align-center a">
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
        document.getElementById("b_find").addEventListener("click", function(e) {
            e.preventDefault();

            var _ppid = document.getElementById("s_ppid").value;



            var _url = "<?= htmlspecialchars($_SERVER['SRVROOT']) ?>/auth/db.php?r=ppfind&ppid=" + _ppid + "&userid=<?= htmlspecialchars($_SESSION['userid']) ?>";
            var XHttp = new XMLHttpRequest();
            var _json = {};
            XHttp.open("GET", _url, true);
            XHttp.send();
            XHttp.


            document.getElementById("date").value = _json.date;
            document.getElementById("hours").value = Math.floor(_json.minutes / 60);
            document.getElementById("minutes").value = _json.minutes % 60;

        });
    </script>
</body>

</html>