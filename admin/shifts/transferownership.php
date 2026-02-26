<?php

include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

echo $_GET['id'];
$shiftid = $_GET['id'];
$shiftsql = $con->prepare("SELECT * FROM `shifts` WHERE `uuid`=?");
$shiftsql->bind_param("s", $shiftid);
$shiftsql->execute();
$shiftresult = $shiftsql->get_result();
$shiftrow = $shiftresult->fetch_assoc();

$accsql = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$accsql->bind_param("s", $shiftrow['userid']);
$accsql->execute();
$accresult = $accsql->get_result();
$accrow = $accresult->fetch_assoc();



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
                <h2 class="text-center text-2xl mb-5">ADMIN | Edit Shift</h2>
                <div class="flex flex-col justify-center items-center w-full">
                    <form class="w-full max-w-lg" method="post">

                        <div class="grid grid-cols-3 grid-rows-2 gap-4">
                            <div class="col-span-3">
                                <h3 class="text-center text-xl"></h3>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                        <label for="date">Old Owner</label>
                                        <input type="text" class="max-w-full" name="date" value="<?= $shiftrow['userid'] ?>">

                                    </div>
                                    <div class="p-2 bg-slate-200 rounded border border-black border-solid">
                                        <label for="date">New Owner</label>
                                        <select name="newowner">
                                            
                                        </select>

                                    </div>
                                    
                                </div>
                            </div>
                            

                            <div class="p-2 bg-slate-200 rounded border1 ">
                                <button type="submit" class="w-full ">Update</button>
                            </div>
                            <div class="p-2  rounded border1  text-center bg-red-100">
                                <a class="w-full  text-center text-red-700 " href="./delete.php?a=0&id=<?php echo $row['ppid'] . "&r=index.php"; ?>">Delete Shift</a>
                            </div>
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