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


$sql2 = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=? AND `ppid`=? ORDER BY `date` DESC LIMIT 1;");
$sql2->bind_param("ss", $_SESSION['userid'], $_SESSION['ppid']);
$sql2->execute();
$res1 = $sql2->get_result();
$ppdata = $res1->fetch_assoc();



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>

    <link rel="stylesheet" href="../../../app/styles/global.css">
</head>

<body class="w-screen">
    <?php if (isset($_GET['e'])) {

        $errModalCode = ERRORS[$_GET['e']];
        include $_SERVER['DOCUMENT_ROOT'] . "/components/error/modal.php";
    } ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>
    <main>

        <section class="place-content-center">
            <div class="flex flex-col justify-center items-center p-3 m-4 border-solid rounded-4xl  border-4 border-black shadow-2xl">
                <h2 class="text-center text-2xl mb-5">New Pay Check</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <form class="w-full max-w-md" method="post">
                        <div class="grid grid-cols-2 gap-4">

                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">User ID</label>
                                <input type="text" class="max-w-full" name="userid" readonly value="<?php echo $_SESSION['userid']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="userid">Pay Period ID</label>
                                <input type="text" class="max-w-full" name="ppid" readonly value="<?= $_SESSION['ppid'] ?>">
                            </div>

                            <div class="p-2 bg-slate-200 col-span-2 rounded border border-black border-solid">
                                <label class="text-lg" for="periodID">Date</label>
                                <input type="date" id="date" class="max-w-full text-right" name="date" required>
                            </div>

                            <div class="p-2 bg-green-200 rounded border border-black border-solid">
                                <label for="payrate">Pay Rate</label>
                                <input type="number" class="max-w-full border border-black border-solid" step="0.01" name="payrate" value="<?= $ppdata['rate'] ?>">
                            </div>
                            <div class="p-2 bg-green-200 rounded border border-black border-solid">
                                <label for="periodID">Tips</label>
                                <input type="number" class="max-w-full" name="tips" step="0.01" required value="0">
                            </div>
                            
                            <div class="p-2 bg-blue-200 rounded border border-black border-solid">
                                <label for="periodID">Hours</label>
                                <input type="number" id="hours" class="max-w-full" name="hours" step="0.000001" required value="<?= floor($ppdata['hours']) ?>">
                            </div>

                            <div class="p-2 bg-blue-200 rounded border border-black border-solid">
                                <label for="periodID">Minutes</label>
                                <input type="number" id="minutes" class="max-w-full" name="minutes" step="1" required value="<?= ($ppdata['hours']-floor($ppdata['hours']))*60 ?>">
                            </div>


                            
                            <div class="p-2 bg-red-200 rounded border border-black border-solid">
                                <label for="periodID">Tax</label>
                                <input type="number" class="max-w-full" name="tax" step="0.01" required value="0">
                            </div>
                            <div class="p-2 bg-red-200 rounded border border-black border-solid">
                                <label for="periodID">Deductions</label>
                                <input type="number" class="max-w-full" name="deductions" step="0.01" required value="0">
                            </div>


                            <div class="p-2 bg-slate-200 col-span-full rounded border1 text-center text-2xl align-center a">
                                <button type="submit" class="w-full">Next</button>
                            </div>
                        </div>

                    
                    </form>
                </div>
            </div>
        </section>


    </main>



</body>

</html>