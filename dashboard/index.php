<?php
session_start();
$_SESSION['profileIMG'] = "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&amp;fit=crop&amp;q=80&amp;w=1160";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>
</head>

<body class="w-screen">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>
    <main>
        <section>
            <div class="flex flex-col justify-center items-center ">
                <h2 class="text-center">Pay Periods</h2>
                <div aria-label="Pay period controls">
                    <div class="flex flex-col justify-center items-center">
                        <button id="newpp" class="w-auto inline-block"><i class="fa-solid fa-square-plus"></i> New Pay Period</button>
                    </div>
                </div>
                <table id="payperiods">
                    <?php
                    include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
                    $sql = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=?");
                    $sql->bind_param("s", $_SESSION['userid']);
                    $sql->execute();
                    $result = $sql->get_result();

                    if ($result->num_rows == 0) {
                        echo "<h2 class='text-center'>You have no payperiods</h2>";
                    }
                    ?>
                </table>
            </div>
        </section>
    </main>





    <?php include  $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
    <script>
        document.getElementById("newpp").addEventListener("click", function() {
            window.location.href = "payperiods/new";
        });
    </script>

</body>

</html>