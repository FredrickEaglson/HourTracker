<?php
session_start();
$_SESSION['profileIMG'] = "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&amp;fit=crop&amp;q=80&amp;w=1160";




function hoursMins($hours)
{
    $hours = floor($hours);
    $mins = floor(($hours - floor($hours)) * 60);
    return $hours . ":" . $mins;
}
$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=? LIMIT 5");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();


$sql2 = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? ");
$sql2->bind_param("s", $_SESSION['userid']);
$sql2->execute();
$numshifts = $sql2->get_result()->num_rows;

$sql3 = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=? ORDER BY DATE DESC  LIMIT 8 OFFSET ?; ");
$page = (intval($_GET['spage']??1) - 1 ) * 8 ?? 8;
$sql3->bind_param("si", $_SESSION['userid'], $page);
$sql3->execute();
$shifts=$sql3->get_result();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>
</head>

<body class="w-screen">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>
    <main class="m-6 border border-black radius-6 p-4 grid grid-cols-2">
        <section class="flex flex-col">
            <div class="flex flex-col justify-center items-center ">
                <h2 class="text-center text-lg font-bold text-teal-950 nunito-bold">Pay Periods</h2>
                <div aria-label="Pay period controls">
                    <div class="flex flex-col justify-center items-center">
                        <button id="newpp" class="w-auto inline-block"><i class="fa-solid fa-square-plus"></i> New Pay Period</button>
                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <?= $result->num_rows ?> Pay Periods
                    </div>
                </div>
                <ul>
                    <?php



                    if ($result->num_rows == 0) {
                        echo "<h2 class='text-center'>You have no payperiods</h2>5";
                    } else {
                        foreach ($result as $row) {
                            echo '
                    <li>
                    <a href="payperiods/edit.php?id=' . $row['ppid'] . '">
                        <div
                            
                            class="flex flex-col justify-center items-center p-3 m-4 border border-black text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]">
                            
                            <span class="flex flex-row w-full justify-between ">
                                <h3
                                    class="flex flex-row mr-6 text-lg payperiodname"
                                    aria-label="pay period name">
                                    ' . $row['name'] ?? date('M D, Y', strtotime($row['startdate']));
                            echo '
                                </h3>
                                <span class="flex flex-row align-bottom text-lg">
                                    <span class="flex flex-row mr-2 text-lg ">' . date('F d, Y', strtotime($row['startdate'])) . ' - ' . date('M d, Y', strtotime($row['enddate']));
                            echo '</span>
                                    <span class="flex flex-row text-red-700 text-lg ">' . $formatter->formatCurrency($row['rate'], "USD");
                            echo '</span>  
                            <span class="flex flex-row ml-2 text-lg ">' . $row['shifts'];
                            echo '</span> 
                                </span>
                            </span>
                            
                        </div>
                    </li></a>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </section>
        <section>
            <div class="flex flex-col justify-center items-center ">
                <h2 class="text-center text-teal-950 font-bold text-lg nunito-bold">Shifts</h2>

                <div aria-label="Pay period controls">
                    <div class="flex flex-col justify-center items-center">
                        <button id="newpp" class="w-auto inline-block"><i class="fa-solid fa-square-plus"></i> New Pay Period</button>
                    </div>
                    <div class="flex flex-col justify-center items-center ">
                        <?= $numshifts ?> Shifts
                    </div>
                </div>

                <div>
                    <table class="mb-4">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Hours</th>
                                <th>Rate</th>
                                <th>Pretax</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($shifts as $shift): ?>

                                <tr>
                                    <td><?= $shift['date'] ?></td>
                                    <td><?= $shift['clockin'] ?></td>
                                    <td><?= $shift['clockout'] ?></td>
                                    <td><?= round($shift['hours'],2) ?></td>
                                    <td><?= $shift['rate'] ?></td>
                                    <td><?= round($shift['rate'] * $shift['hours'],2)?></td>
                                </tr>

                            <?php endforeach; 
                        
                            ?>
                        </tbody>
                    </table>
                    <div aria-label="shift pagination controls" class=" text-center">
                        <?php
                        if (isset($_GET['spage'])&& $_GET['spage'] > 1) {
                            echo "<a class='text-center ml-2 text-teal-900' href='./?spage=" . ($_GET['spage'] - 1) . "'>&lt; Previous  </a>";
                        } 
                        if (isset($_GET['spage'])&& $_GET['spage']< intdiv($numshifts , 8 )+1) {
                            echo "<a class='text-center mr-2 text-teal-900' href='./?spage=" . ($_GET['spage'] + 1) . "'>  Next &gt;</a>";
                        } else if (!isset($_GET['spage'])) {
                             echo "<a class='text-center mr-2 text-teal-900' href='./?spage=2'>Next &gt;</a>";
                            
                        }
                        ?>
                    </div>
                    <p class="text-center">Showing page <?= $_GET['spage']??1 ?> of <?= intdiv($numshifts , 8 )+1?></p>

                </div>
            </div>
            </div>
        </section>
    </main>





    <?php include  $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
    <script>
        document.getElementById("newpp").addEventListener("click", function() {
            window.location.href = "payperiods/new";
        });
    </script>

</body>

</html>