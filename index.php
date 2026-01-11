<?php
session_start();



if (isset($_SESSION['userid']) && $_SESSION['loggedin'] == true) {
    header("Location: http://localhost/dashboard/index.php");
}
$errs='';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_destroy();

    $email = $_POST['username'];
    include 'auth/dbcon.php';
    $sql = $con->prepare("SELECT * FROM `accounts` WHERE `email`=?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows == 0) {
        $errs= "Invalid Email";
    } else if ($result->num_rows ==1) {
        $row = $result->fetch_assoc();
        if (password_verify($_POST['password'], $row['passwordHash'])) {
            session_start();
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['email']=$row['email'];
            $_SESSION['loggedin'] = true;
            $_SESSION['prefferedName'] = $row['prefferedName'];
            $_SESSION['tempshifts'] = ["x"];
            $_SESSION['defaultrate']=$row['defaultrate'];
            $_SESSION['expire']=time()+60*60*24*3;


            header("Location: dashboard");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HourTracker | Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body style="background:darkgrey" class="h-dvh w-dvw flex flex-col justify-center items-center text-2xl">
    <div class="flex flex-col justify-center items-center  border-solid rounded-4xl  border-4 border-black shadow-2xl" style="background:grey">
        <div class="flex m-10 flex-col justify-center items-center">
            <form id="f_login" method="post">
                <div class="flex flex-col justify-center items-center max-w-90">
                    <h1 class="text-center">You must sign in to access this service.</h1>
                    <p class="text-center text-sm text-red"><span><?php echo $errs; ?></span></p>
                    <div class="m-3 text-slate-300 border rounded">

                        <input class="p-1 rounded" type="text" name="username" id="username" placeholder="Email">
                    </div>
                    <div class="m-3 text-slate-300 border rounded">

                        <input class="p-1 rounded" type="password" name="password" id="password" placeholder="Password">
                    </div>
                    <div class="m-3 text-slate-300 border rounded">
                        <button id="login" type="submit" class="bg-blue-200 hover:bg-blue-400 text-slate-700 font-bold py-2 px-4 rounded">Login</button>

                    </div>
                    <div>
                        <p class="text-sm">Don't have an account? <a href="auth/?r=reg" class="text-blue-200 underline hover:no-underline">Register Here.</a></p>
                    </div>
                </div>

        </div>


        </form>
    </div>

    <script>
        document.getElementById('username').focus();

        document.addEventListener('keydown',function(e) {
            if (e.key==="Enter") {
                document.getElementById("login").submit();
            }
        });

    </script>
</body>

</html>