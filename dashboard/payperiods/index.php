<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/auth/dbcon.php";
$sql = $con->prepare("SELECT * FROM `payperiods` WHERE `userid`=?");
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
    <main class="flex flex-col justify-center items-center m-auto p-auto mb-16 pb-4">
        <section class="flex flex-col justify-center items-center m-4 p-4"></section>
        <table class="border1 border-collapse gap-2">
            <tr class="border1 border-collapse gap-2">
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Rate</th>
                <th>Edit</th>
            </tr>
            <?php



            if ($result->num_rows == 0) {
            } else {

                foreach ($result as $row) {
                    echo "<tr class='border-collapse'>";

                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . date('M d, Y', strtotime($row['startdate'])) . "</td>";
                    echo "<td>" . date('M d, Y', strtotime($row['enddate'])) . "</td>";
                    echo "<td>" . $row['rate'] . "</td>";
                    echo " <td><a href=\"edit.php?id=" . $row['ppid'] . "\">Edit</a></td>";
                    echo  "</tr>";
                }
            }

            ?>
        </table>
        </section>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
</body>

</html>