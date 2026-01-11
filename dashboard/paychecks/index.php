<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";

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
                        foreach ($result as $row): ?>

                            <li>
                                <a href="edit.php?id=<?= $row['pcid'] ?>">
                                    <div class="flex flex-col justify-center items-center p-3 m-4 border border-black text-lg text-inherit rounded-4xl border-4 border-black shadow-2xl min-w-[30rem]">

                                        <div class="grid grid-cols-5 gap-4 text-center">
                                            <div>
                                                Date
                                                M.D.Y
                                            </div>
                                            <div>
                                                Hours
                                            </div>
                                            <div>
                                                Rate
                                            </div>
                                            <div>
                                                Post Tax Rate
                                            </div>
                                            <div>
                                                Net
                                            </div>

                                            <div>
                                                <?= date('m.d.y',strtotime($row['date'])) ?>
                                            </div>
                                            <div>
                                                <?=  $row['hours'] ?>
                                            </div>
                                            <div>
                                                <?= $row['rate'] ?>
                                            </div>
                                            <div class="text-red-700">
                                                <?= $row['realrate'] ?>
                                            </div>
                                            <div>
                                                <?= $row['net'] ?>
                                            </div>
                                        </div>
                                    </div>


            </div>
            </a>
            </li>


    <?php endforeach;
                    } ?>
    </ul>
    </div>
        </section>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
</body>

</html>