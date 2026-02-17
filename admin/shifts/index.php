<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";


include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";



$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);
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

<body class="w-full">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/admin.header.php"; ?>
    <main>
        <section class="place-content-center">
            <div class="flex flex-col justify-center items-center p-3 m-4 border-solid rounded-4xl  border-4 border-black shadow-2xl">

                <h2 class="text-center text-2xl mb-5">Shifts</h2>
                <div class="flex flex-col justify-center items-center w-full">

                    <div class="p-2 bg-slate-200 rounded border1 mt-2 h-full">
                        <a href='./new' class="w-full h-full">Add Shifts</a>
                    </div>



                    <div class="flex flex-col justify-center items-center p-3 m-4">
                        <div class="mb-5">
                            <ul>
                                <?php
                                $sql = $con->prepare("SELECT * FROM `shifts` ORDER BY `date` DESC");

                                $sql->execute();
                                $result2 = $sql->get_result();
                                if ($result2->num_rows > 0) {
                                    foreach ($result2 as $row2) :
                                        if ($row2['userid'] != HT_ADMIN_USERID && isset($_GET['adminonly']) && $_GET['adminonly'] == "true") {
                                            continue;
                                        } ?>


                                        <a href="./edit?id=<?= $row2['uuid'] ?>">
                                            <li>
                                                <div

                                                    class="flex flex-col justify-center items-center p-3 m-4 border
                           <?php if ($row2['worked'] == FALSE) {
                                            echo " border-yellow-600 ";
                                            //admin userid
                                        } else if ($row2['userid'] == "45eb3dc0-f9a7-11f0-9685-a029190ac76c") {
                                            echo " border-green-600 ";
                                        } else {
                                            echo " border-black ";
                                        }

                            ?>
                           text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]">

                                                    <span class="flex flex-row w-full justify-between ">
                                                        <h3
                                                            class="flex flex-row mr-6 text-lg payperiodname"
                                                            aria-label="pay period name">
                                                            <?= date('D, M d, Y', strtotime($row2['date'])); ?>

                                                        </h3>
                                                        <span class="flex flex-row align-bottom text-lg">
                                                            <span class="flex flex-row mr-2 text-lg "><?= floor($row2['minutes'] / 60) . ':' . formatmins(floor($row2['minutes'] % 60)); ?>
                                                            </span>
                                                            <span class="flex flex-row text-red-700 text-lg ">
                                                                <?php if (isset($row2['rate'])) {
                                                                    echo $formatter->formatCurrency($row2['rate'], "USD");
                                                                } ?>
                                                            </span>
                                                            <span class="flex flex-row ml-2 text-lg "> <?= $formatter->formatCurrency($row2['rate'] * $row2['minutes'] / 60, "USD"); ?>
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

                </div>
            </div>
        </section>
    </main>


    <script>

    </script>
</body>

</html>