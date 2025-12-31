<?php
session_start();
$defaultrate = $_SESSION['defaultrate'];

$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $rate = $_POST['payrate'];
    $userid = $_POST['userid'];
    $hours = $_POST['hours'] + $_POST['minutes'] / 60;
    $ppid = $_POST['periodID'] ?? '';

    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
    $sql = $con->prepare("INSERT INTO `shifts` (`date`, `rate`, `userid`, `hours`, `ppid`) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("sssss", $date, $rate, $userid, $hours, $ppid);
    $sql->execute();
    header("Location: ../");

    if ($_POST['periodID'] != '') {
        $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=?");
        $sql->bind_param("s", $_POST['periodID']);
        $sql->execute();
        $result = $sql->get_result();
        if ($result->num_rows == 0) {
        } else {
            $row3 = $result->fetch_assoc()[0];
            $pphours = $hours + $row3['hours'];
            $ppshiftids = $shifts . ',' . $row3['shift_ids'];
            $ppshifts = $row3['shifts'] + 1;

            $sql = $con->prepare("UPDATE `payperiods` SET `hours`=?, `shifts`=?,shift_ids=?,worked=? WHERE `ppid`=?");
            $sql->bind_param("ssss", $pphours, $ppshifts, $ppshiftids,$_POST['worked'], $_POST['periodID']);
            $sql->execute();
        }
    }
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
                <h2 class="text-center text-2xl mb-5">New Shift</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <form class="w-full max-w-md" method="post">

                        <div class="grid grid-cols-3 grid-rows-2 gap-4">
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="startdate">Date</label>
                                <input type="date" class="max-w-full" name="date" value="<?php echo date("Y-m-d", time()-(60*60*24)); ?>" required>
                            </div>

                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="payrate">Pay Rate</label>
                                <input type="number" class="max-w-full border border-black border-solid pl-1" step="0.01" name="payrate" value="<?php echo $defaultrate ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">User ID</label>
                                <input type="text" class="max-w-full" name="userid" readonly value="<?php echo $_SESSION['userid']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">Hours</label>
                                <input type="number" class="max-w-full border border-black border-solid pl-1" name="hours" required>
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">Minutes</label>
                                <input type="number" class="max-w-full border border-black border-solid pl-1" name="minutes" required value="0">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">Worked?</label>
                                <input type="checkbox" class="max-w-full border border-black border-solid pl-1" name="worked" checkeds>
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Shift ID</label>
                                <input type="text" class="max-w-full" name="shiftid" readonly value="N/A">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid col-span-2">
                                <label for="periodID">Pay Period</label>
                                <?php
                                include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
                                $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=? ORDER BY `startdate` DESC");
                                $sql->bind_param("s", $_SESSION['userid']);
                                $sql->execute();
                                $result = $sql->get_result();
                                if ($result->num_rows == 0 && sizeof($_SESSION['tempshifts']) == 1) {
                                    echo "<h2 class='text-center'>You have no payperiods</h2>";
                                    echo "<input type='hidden' name='' value=''>";
                                } else {
                                    echo "<select name='periodID'>";
                                    
                                    foreach ($result as $row) {
                                        echo "<option value='" . $row['ppid'] . "'>";
                                        echo '<div
                            
                            class="flex flex-col justify-center items-center p-3 m-4 border border-black text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]">
                            
                            <span class="flex flex-col w-full justify-between ">
                                <h3
                                    class="flex flex-row mr-6 text-lg payperiodname"
                                    aria-label="pay period name">
                                    ' . date('M j, Y', strtotime($row['startdate'])) . ' - ' . date('M j, Y', strtotime($row['enddate']));
                                        echo '
                                </h3>
                                <br>
                                <span class="flex flex-row align-bottom text-lg">
                                    
                                    <span class="flex flex-row text-red-700 text-lg ">' . $formatter->formatCurrency($row['rate'], "USD");
                                        echo '</span>  ';
                                        echo '
                                </span>
                            </span>
                            
                        </div>';
                                        echo "</option>";
                                    }
                                }
                                echo "</select>"
                                ?>
                            </div>
                            <div class="p-2 bg-slate-200 rounded border1 justify-center items-center align-center align-items-center">
                                <button type="submit" class="w-full h-full">Insert</button>
                            </div>
                        </div>

                        <div class="flex flex-col justify-center items-center p-3 m-4">
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