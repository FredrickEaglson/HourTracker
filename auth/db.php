<?php
session_start();

$pass = "eYY)]ajDe274n_qJ";
$user = "hourtracker";
$db = "hourtracker";
$server = "localhost";

$con = mysqli_connect($server, $user, $pass, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['r']) {
        case 'emch':
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                $email = $_POST['email'];
            $sql = $con->prepare("SELECT * FROM accounts WHERE email=?");
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();
            if ($result->num_rows == 0) {
                //false indicates email not found
                echo false;
            } else {
                //true indicates email found
                echo true;
            }
            break;
        case 'pswd':
            $pattern = `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/`;
            $pswrd1 = $_POST['pswrd1'];
            $pswrd2 = $_POST['pswrd2'];
            if ($pswrd1 != $pswrd2) {
                echo false;
            } else if (preg_match($pattern, $pswrd1) && preg_match($pattern, $pswrd2)) {
                echo true;
            }
            break;

        case 'newshift':

            $hours = $_POST['shifthours'] + $_POST['shiftminutes'] / 60;
            $rate = $_POST['shiftrate'];
            $sql = $con->prepare("INSERT INTO `shifts` (`userid`, `date`, `hours`, `minutes`, `rate`) VALUES (?, ?, ?, ?, ?)");
            $sql->bind_param("ssssd", $_SESSION['userid'], $_POST['shiftdate'], $hours, $_POST['shiftminutes'], $rate);
            $result = $sql->execute();
            if ($result) {
                $sql = $con->prepare("SELECT `uuid` FROM `shifts` WHERE `userid`=? AND `ppid`=NULL ORDER BY `creation_date` DESC LIMIT 1");
                $sql->bind_param("s", $_SESSION['userid']);
                $sql->execute();
                $result = $sql->get_result();
                if ($result->num_rows == 0) {
                    echo "false";
                } else {
                    $temp = $_SESSION['tempshifts'];
                    $temp .= $result->fetch_assoc()['uuid'];
                    $_SESSION['tempshifts'] = $temp;
                    echo "true";
                }
            }
    }
}

function validatePasswordStrength($password)
{
    $errors = [];

    // Minimum length
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // At least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must include at least one uppercase letter.";
    }

    // At least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must include at least one lowercase letter.";
    }

    // At least one number
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must include at least one number.";
    }

    // At least one special character (non-alphanumeric)
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = "Password must include at least one special character.";
    }

    return $errors; // Returns an array of errors, or an empty array if strong
}
