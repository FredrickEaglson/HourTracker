<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/types/tasks.php";

$sql = $con->prepare("SELECT * FROM `tasks` WHERE `userid`=? AND `type`='pp'");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>
</head>

<body class="w-screen">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>

    <main class="m-3 p-3 h-full">



        <section class="p-4 border-[3px] rounded-3xl border-black border-solid flex flex-col">
            <div class="flex flex-col justify-center items-center ">
                <h2 class="text-center text-lg font-bold text-teal-950 nunito-bold">Pay Periods</h2>
                <div aria-label="Pay period controls">

                    <div class="flex flex-col justify-center items-center">
                        <h3><?= $result->num_rows ?> Pay Period Automations</h3>
                    <a href="new" class="w-full">

                    <div class="flex flex-row spacebetween w-full">
                        <h2 class="text-lg">
                            <icon class="fa-solid fa-square-plus mr-2"></icon>New Automation
                        </h2>



                    </div>


                </a>
                    </div>
                    
                </div>
                
                <ul class="">
                    <?php foreach ($result as $row): ?>



                    <?php endforeach; ?>
                </ul>
            </div>
        </section>



    </main>





    <?php include  $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>


</body>

</html>