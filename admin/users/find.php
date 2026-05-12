<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/functions.php";
include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";

if ($_SESSION['role'] != "admin") {
    header("Location: /");
}


$id = $_GET['id'];
$return = $_GET['r'];

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$sql = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$sql->bind_param("s", $id);
$sql->execute();
$result = $sql->get_result();
$user = $result->fetch_assoc();

$sql2 = $con->prepare("SELECT * FROM `shifts` WHERE `userid`=?");
$sql2->bind_param("s", $id);
$sql2->execute();
$result2 = $sql2->get_result();
$numshifts = $result2->num_rows;
$shifts = $result2->fetch_assoc();

$sql3 = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=?");
$sql3->bind_param("s", $id);
$sql3->execute();
$result3 = $sql3->get_result();
$payperiods = $result3->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>

<body class="w-screen">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/admin.header.php"; ?>
    <main class="m-3 p-3 gap-2">
        <section class="p-4 border-[3px] rounded-3xl border-black border-solid flex flex-col">
            <div class="flex flex-col justify-center items-center ">
                <h2 class="text-center text-teal-950 font-bold text-lg nunito-bold">User Details</h2>
                <div class=" grid grid-cols-2 gap-4 justify-center items-center">
                    <div class="col-span-2 grid grid-cols-3">
                        <div class="flex flex-col justify-center items-center">
                            <div class="flex flex-col justify-center items-center">
                                <h3 class="text-center text-xl">First Name</h3>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <p class="text-center text-teal-900"><?= $user['firstname'] ?></p>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <div class="flex flex-col justify-center items-center">
                                <h3 class="text-center text-xl">Middle Name</h3>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <p class="text-center text-teal-900"><?php if ($user['middlename'] == "") { ?> <span class="text-red">N/A</span> <?php } else { ?> <?= $user['middlename'] ?> <?php } ?></p>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <div class="flex flex-col justify-center items-center">
                                <h3 class="text-center text-xl">Last Name</h3>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <p class="text-center text-teal-900"><?= $user['lastname'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <div class="flex flex-col justify-center items-center">
                            <h3 class="text-center text-xl">User ID</h3>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <p class="text-center text-teal-900"><?= $user['userid'] ?></p>
                        </div>

                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <div class="flex flex-col justify-center items-center">
                            <h3 class="text-center text-xl">Email</h3>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <p class="text-center text-teal-900"><a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></p>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <div class="flex flex-col justify-center items-center">
                            <h3 class="text-center text-xl">Last Login</h3>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <p class="text-center text-teal-900"><?= $user['lastLogin'] ?></p>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <div class="flex flex-col justify-center items-center">
                            <h3 class="text-center text-xl">Date Created</h3>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <p class="text-center text-teal-900"><?= $user['dateCreated'] ?></p>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center items-center">
                        <div class="flex flex-col justify-center items-center">
                            <h3 class="text-center text-teal-900 text-xl"><a href="../shifts/index.php?id=<?= $user['userid'] ?>">Go to shifts</h3>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </main>
</body>

</html>