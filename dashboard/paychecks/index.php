<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$sql = $con->prepare("SELECT * FROM `paychecks` WHERE `userid`=?");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>

</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php";


    ?>
    <main class="flex flex-col mt-5 justify-center items-center m-auto p-auto mb-16 pb-4">
        <section>
            <div class="flex flex-col justify-center items-center ">
                <h2 class="text-center">Pay Checks</h2>
                <div aria-label="Pay Check controls">
                    <div class="flex flex-col justify-center items-center">
                        <a href="new" class="w-auto inline-block"><i class="fa-solid fa-square-plus"></i> New Pay Check</a>
                    </div>
                </div>
                <ul>
                    <?php
                    $formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);
                    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
                    $sql = $con->prepare("SELECT * FROM `paychecks` WHERE `userid`=?");
                    $sql->bind_param("s", $_SESSION['userid']);
                    $sql->execute();
                    $result = $sql->get_result();

                    if ($result->num_rows == 0) {
                        echo "<h2 class='text-center'>You have no pay checks</h2>";
                    } else {
                        foreach ($result as $row) {
                            echo '
                    <li>
                    <a href="edit.php?id=' . $row['ppid'] . '">
                        <div
                            
                            class="flex flex-col justify-center items-center p-3 m-4 border border-black text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]">
                            
                            <span class="flex flex-row w-full justify-between ">
                                <h3
                                    class="flex flex-row mr-6 text-lg payperiodname"
                                    aria-label="pay check name">
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
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
</body>

</html>