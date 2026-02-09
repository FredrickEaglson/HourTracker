<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

$sql = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$sql->close();
$row = $result->fetch_assoc();

$sql2 = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=?");
$sql2->bind_param("s", $_SESSION['userid']);
$sql2->execute();
$ppresult = $sql2->get_result();


$sql3 = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=?");
$sql3->bind_param("s", $_SESSION['userid']);
$sql3->execute();
$shiftresult = $sql3->get_result();

$sql4 = $con->prepare("SELECT * FROM `paychecks` WHERE `userid`=?");
$sql4->bind_param("s", $_SESSION['userid']);
$sql4->execute();
$pcresult = $sql4->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = $con->prepare("UPDATE `accounts` SET `csvname`=? WHERE `userid`=?");
    $sql->bind_param("ss", $_POST['csvname'], $_SESSION['userid']);
    $sql->execute();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>
</head>

<body class="w-full">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>

    <main class="m-3 p-6 ml-0 w-full">
        <h1 class="text-4xl roboto-bold">Settings</h1>
        <form method="post" class="w-full">
            <section class="m-auto p-3 grid grid-cols-3 w-full justify-items-center  justify-center space-between">
                <div class="border rounded-4xl">
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">First Name<span class="text-red-700">*</span></label><br>
                        <input type="text" class="max-w-full border p-1 rounded-full" name="firstname" required value="<?= $row['firstname'] ?>">
                    </div>
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">Middle Name</label><br>
                        <input type="text" class="max-w-full border p-1 rounded-full" name="middlename" value="<?= $row['middlename'] ?>">
                    </div>
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">Last Name<span class="text-red-700">*</span></label><br>
                        <input type="text" class="max-w-full border p-1 rounded-full" name="lastname" required value="<?= $row['lastname'] ?>">
                    </div>
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">CSV Name<span class="text-red-700">*</span></label><br>
                        <input type="text" class="max-w-full border p-1 rounded-full" name="csvname" required value="<?= $row['csvname'] ?>">

                    </div>
                </div>
                <div class="border rounded-4xl">
                    <div class="p-2 m-3 max-w-100 text-center">
                        <a href="<?= $_SERVER['SRVROOT'] ?>/dashboard/payperiods">
                            <p class="nunito-bold" style="font-size:1.5rem">Total Pay Periods:
                                <span class="max-w-full"><?= $ppresult->num_rows ?></span>
                            </p>
                        </a>
                    </div>

                    <div class="p-2 m-3 max-w-100 text-center">
                        <a href="<?= $_SERVER['SRVROOT'] ?>/dashboard/shifts">
                            <p class="nunito-bold" style="font-size:1.5rem">Total Shifts:
                                <span class="max-w-full"><?= $shiftresult->num_rows ?></span>
                            </p>
                        </a>
                    </div>

                    <div class="p-2 m-3 max-w-100 text-center">
                        <a href="<?= $_SERVER['SRVROOT'] ?>/dashboard/paychecks">
                            <p class="nunito-bold" style="font-size:1.5rem">Total Pay Checks:
                                <span class="max-w-full"><?= $pcresult->num_rows ?></span>
                            </p>
                        </a>
                    </div>
                </div>
                <div class="border rounded-4xl">
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label for="weekstartday" class="nunito-bold">Week Start Day</label><br>
                        <select name="weekstartday">
                            <option value="0">Sunday</option>
                            <option value="1" selected>Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                        </select>
                    </div>
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">Pay Period Length</label><br>
                        <input type="number" class="max-w-full border p-1 rounded-full" name="lastname" required value="<?= $row['pplength'] ?>">
                    </div>

                </div>
                <div class="p-2 m-3 max-w-100 text-center">
                    <button type="submit">Save</button>
                </div>
            </section>
        </form>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
</body>

</html>