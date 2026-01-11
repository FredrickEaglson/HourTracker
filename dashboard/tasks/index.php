<?php 

include $_SERVER['DOCUMENT_ROOT']."/app/types/tasks.php";

include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

$sql = $con->prepare("SELECT * FROM `tasks` WHERE `userid`=? ORDER BY `effective_date` DESC LIMIT 5");
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
                <h2 class="text-center text-lg font-bold text-teal-950 nunito-bold">Tasks</h2>
                <div aria-label="Pay period controls">
                    
                    <div class="flex flex-col justify-center items-center">
                        <?= $result->num_rows ?> Tasks
                    </div>
                </div>
                <ul class="">
                    <?php foreach (constant("TASK_TYPES") as $type): ?>

                        <a href="<?= $type['pathname'] ?>" class="w-full">
                            <li class="w-full">
                                <div class="flex flex-row spacebetween w-full">
                                    <h2 class="text-lg"><icon class="<?= $type['icon'] ?> mr-2"></icon><?= $type['name'] ?></h2>
                                    
                                    
                                    
                                </div>
                                
                            </li>
                        </a>

                    <?php endforeach; ?>                    
                </ul>
            </div>
        </section>

        
    
    </main>





    <?php include  $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
    

</body>

</html>