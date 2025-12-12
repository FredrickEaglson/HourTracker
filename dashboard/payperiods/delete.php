<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

$id = $_GET['id'];

$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `ppid`=? && `userid`=?");
$sql->bind_param("ss", $id, $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
$ppid = $row['ppid'];
$formatter = new NumberFormatter("en_US", NumberFormatter::CURRENCY);


if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>

</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php";

    ?>
    <main>
        <section class="place-content-center">
            <div class="flex flex-col justify-center items-center p-3 m-4 border-solid rounded-4xl  border-4 border-black shadow-2xl">
                <h2 class="text-center text-2xl mb-5 text-red-700">DELETE Pay Period</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <div class="w-full max-w-md">

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
                                <label for="periodID">Pay Period ID</label>
                                <input type="text" class="max-w-full" name="payperiodID" readonly value="<?php echo $row['ppid']; ?>">
                            </div>
                            <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                <label for="shifts">Shifts</label>
                                <input type="text" class="max-w-full" name="shifts" readonly value="<?php echo $row['shifts']; ?>">
                            </div>

                        </div>

                        <section class="mt-10">
                            <form method="post">
                                <div class="p-2 bg-slate-200 rounded border1 mt-2 h-full">
                                    <h2>Please verify the information above matches the Pay Payperiod you want to delete.</h2>
                                </div>
                                <div class="p-2 bg-slate-200 rounded border1 mt-2 h-full">
                                    <input type="checkbox" name="accept1" required><label for="accept1"> The information above is correct.</label><br>
                                    <input type="checkbox" name="accept2" required><label for="accept2"> The action I'm about to preform is permenant and cannot be undone.</label><br>
                                    <input type="hidden" name="id" value="<?php echo $row['ppid']; ?>">
                                    <input type="hidden" name="r" value="index.php">
                                    <input type="hidden" name="a" value="0">

                                </div>
                                <div class="p-2 bg-slate-200 rounded border1 mt-2 h-full">
                                    <label for="pwverify">Password Verification:</label><br>
                                    <input type="password" class="border w-full border-solid border-black outline-none" name="pwverify" required>
                                </div>
                                <div class="p-2 bg-slate-200 rounded border1 mt-2 h-full flex flex-col justify-center">
                                    <button class="border border-black border-solid text-xl" type="submit">Confirm</button>
                                </div>
                            </form>
                        </section>




                    </div>
                </div>

            </div>
            </div>
        </section>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
</body>

</html>