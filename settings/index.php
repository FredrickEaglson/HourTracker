<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$sql = $con->prepare("SELECT * FROM `accounts` WHERE `userid`=?");
$sql->bind_param("s", $_SESSION['userid']);
$sql->execute();
$result = $sql->get_result();
$sql->close();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>
</head>

<body class="w-screen">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>

    <main class="m-3 p-6 ml-0">
        <h1 class="text-4xl roboto-bold">Settings</h1>
        <form method="post">
            <section class="m-3 p-3 grid grid-cols-3 flex flex-col justify">
                <div class="p-2 m-3 max-w-100 col-span-3 flex flex-row rounded-3xl border text-center border-black border-solid">
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">First Name<span class="text-red-700">*</span></label><br>
                        <input type="text" class="max-w-full border p-1 rounded-full" name="firstname" required value="<?= $row['firstname'] ?>">
                    </div>
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">Middle Name<span class="text-red-700">*</span></label><br>
                        <input type="text" class="max-w-full border p-1 rounded-full" name="middlename" required value="<?= $row['middlename'] ?>">
                    </div>
                    <div class="p-2 m-3 max-w-100 text-center">
                        <label class="nunito-bold" for="">Last Name<span class="text-red-700">*</span></label><br>
                        <input type="text" class="max-w-full border p-1 rounded-full" name="lastname" required value="<?= $row['lastname'] ?>">
                    </div>
                </div>
            </section>
        </form>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
</body>

</html>