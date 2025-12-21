<?php
session_start();
$defaultrate = 0.0;
$totaltime = 0;
$totalbt = 0;
$ppid = '';
include "../..//auth/dbcon.php";

$id = $_GET['id'];

$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shiftids = $row['shift_ids'] . ',' . implode(',', $_POST['shifts']);
    $sql = $con->prepare("UPDATE `payperiods` SET `name`=?, `startdate`=?, `enddate`=?, `rate`=?, `shift_ids`=? WHERE `ppid`=?");
    $sql->bind_param("sssdss", $row['name'], $row['startdate'], $row['enddate'], $row['rate'], $shiftids, $row['ppid']);
    $sql->execute();
    $result = $sql->get_result();
    echo $sql->error;
    echo $sql->affected_rows;
    if ($sql->affected_rows > 0) {
        header("Location: ../payperiods/update.php?id=" . $row['ppid'] . '&r=' . $_SERVER['REQUEST_URI'] . '&e=1');
    } else {
        echo "Error";
        echo $sql->error;
    }
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
                                <label for="name">Name</label>
                                <input type="text" class="max-w-full border border-black" name="name" value="<?php echo $row['name']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="startdate">Start Date</label>
                                <input type="date" class="max-w-full" name="startdate" required value="<?php echo date("Y-m-d", strtotime($row['startdate'])); ?>">
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
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="shifts">Shifts</label>
                                <input type="text" class="max-w-full" name="shifts" readonly value="<?php echo $row['shifts']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Total Time</label>
                                <input type="text" class="max-w-full" name="hours" readonly value="<?php echo floor($row['hours']) . ':' . formatmins(floor(($row['hours'] - floor($row['hours'])) * 60)) ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="periodID">Before Tax</label>
                                <input type="text" class="max-w-full" name="" readonly value="<?php echo $formatter->formatCurrency(($row['money'] ?? $row['rate']*$row['hours']), "USD"); ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border1 h-full">
                                <button type="submit" class="w-full h-full">Update</button>
                            </div>
                            <div class="p-2 bg-slate-200 rounded border1 h-full text-center">
                                <a class="w-full h-full text-center" href="./update.php?id=<?php echo $row['ppid'] . "&r=" . $_SERVER['REQUEST_URI']; ?>">Update Hours</a>
                            </div>
                            <div class="p-2  rounded border1 h-full text-center bg-red-100">
                                <a class="w-full h-full text-center text-red-700 " href="./delete.php?a=0&id=<?php echo $row['ppid'] . "&r=index.php"; ?>">Delete Payperiod</a>
                            </div>
                        </div>

                        <div class="flex flex-col justify-center items-center p-3 m-4">
                            <div class="mb-5">
                                <ul>
                                    <?php
                                    $sql = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? AND `ppid`=?");
                                    $sql->bind_param("ss", $_SESSION['userid'], $row['ppid']);
                                    $sql->execute();
                                    $result2 = $sql->get_result();
                                    if ($result2->num_rows > 0) {
                                        foreach ($result2 as $row2) {

                                            echo '
                    <a href="#">
                    <li>
                        <div
                            
                            class="flex flex-col justify-center items-center p-3 m-4 border border-black text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]">
                            
                            <span class="flex flex-row w-full justify-between ">
                                <h3
                                    class="flex flex-row mr-6 text-lg payperiodname"
                                    aria-label="pay period name">
                                    ' . date('D, M d, Y', strtotime($row2['date']));
                                            echo '
                                </h3>
                                <span class="flex flex-row align-bottom text-lg">
                                    <span class="flex flex-row mr-2 text-lg ">' . floor($row2['hours']) . ':' . formatmins(floor(($row2['hours'] - floor($row2['hours'])) * 60));
                                            echo '</span>
                                    <span class="flex flex-row text-red-700 text-lg ">' . $formatter->formatCurrency($row2['rate'], "USD");
                                            echo '</span>  
                            <span class="flex flex-row ml-2 text-lg ">' . $formatter->formatCurrency($row2['rate'] * $row2['hours'], "USD");
                                            echo '</span> 
                                </span>
                            </span>
                            <span>
                            <a class="text-red-500 underline" href="./deallocate.php?id=' . $row2['uuid'] . '&ppid=' . $ppid . '&r=/dashboard/payperiods/edit.php?id=' . $ppid . '?Deallocate">Deallocate</a>
                            </a>
                            </span>
                            
                        </div>
                    </li></a>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>


                            <?php

                            include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
                            $sql = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? AND `ppid` IS null OR `ppid`=''");
                            $sql->bind_param("s", $_SESSION['userid']);
                            $sql->execute();
                            $result = $sql->get_result();
                            echo $result->num_rows;
                            if ($result->num_rows == 0 && sizeof($_SESSION['tempshifts']) == 1) {
                                echo "<h2 class='text-center'>You have no unallocated shifts</h2>";
                                echo "<input type='hidden' name='shifts[]' value=''>";
                            } else {
                                echo '<ul>';
                                foreach ($result as $row) {
                                    echo '<a href="./allocate.php?id=' . $row['uuid'] . '&r=' . $_SERVER['REQUEST_URI'] . '&ppid=' . $ppid . '">
                                                    <li>
                        <div class="flex flex-col justify-center items-center p-3 m-4 border border-red-700 text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]">
                            
                            <span class="flex flex-row w-full justify-between ">
                                <span>
                                
                                <h3
                                    class="flex flex-row mr-6 text-lg payperiodname"
                                    aria-label="pay period name">
                                    ' . date('D, M d, Y', strtotime($row['date']));
                                    echo '
                                </h3>
                                </span>
                                <span class="flex flex-row align-bottom text-lg">
                                    <span class="flex flex-row mr-2 text-lg ">' . floor($row['hours']) . ':' . formatmins(floor(($row['hours'] - floor($row['hours'])) * 60));
                                    echo '</span>
                                    <span class="flex flex-row text-red-700 text-lg ">' . $formatter->formatCurrency($row['rate'], "USD");
                                    echo '</span>  
                            <span class="flex flex-row ml-2 text-lg ">' . $formatter->formatCurrency($row['rate'] * $row['hours'], "USD");
                                    echo '</span> 
                                </span>
                            </span>
                            
                        </div>
                    </li></a>';
                                }
                                echo '</ul>';
                            }

                            ?>


                            <div class="p-2 bg-slate-200 rounded border1 mt-2 h-full">
                                <a href='/dashboard/shifts/new' class="w-full h-full">Add Shifts</a>
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