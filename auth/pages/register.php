<?php
include $_SERVER['DOCUMENT_ROOT']. "/auth/dbcon.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    $fullname = $_POST['fullname'];
    $firstname = $lastname = $middlename = '';
    if (sizeof(explode(" ", $fullname)) == 2) {
        $firstname = explode(" ", $fullname)[0];
        $lastname = explode(" ", $fullname)[1];
    } else {
        $firstname = explode(" ", $fullname)[0];
        $lastname = explode(" ", $fullname)[sizeof(explode(" ", $fullname)) - 1];
        $namearr = explode(" ", $fullname);
        array_shift($namearr);
        $namearr = array_reverse($namearr);
        array_shift($namearr);
        $namearr = array_reverse($namearr);

        $middlename = join(' ', $namearr);
    }

    $email = $_POST['email'];
    $password = $_POST['pswrd1'];
    $password2 = $_POST['pswrd2'];

    $pattern1 = `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/`;

    if ($password != $password2) {
        echo "Passwords do not match";
        die;
    }

    if (sizeof(validatePasswordStrength($password)) > 0 || sizeof(validatePasswordStrength($password2))) {
        $http_response_header["x-password-errors"] = join(";", validatePasswordStrength($password));
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);



    $sql = $con->prepare("INSERT INTO accounts (email, passwordHash, firstname, lastname, middlename, prefferedName) VALUES (?, ?, ?, ?, ?,?)");
    $sql->bind_param("ssssss", $email, $passwordHash, $firstname, $lastname, $middlename, $fullname);
    $sql->execute();
    if ($sql->affected_rows == 1) {
        echo "Account created successfully";
        header("Location: ../", true, 302);
    } else {
        echo "Error: Account not created";
        echo $sql->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | HourTracker</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body style="background:darkgrey" class="h-dvh w-dvw flex flex-col justify-center items-center text-2xl" style="background:grey">
    <div class="flex m-10 flex-col justify-center items-center text-white p-4 rounded-3xl" style="background:grey;">
        <form autocomplete="off" method="post">
            <input type="hidden" aria-hidden="true" name="r" value="reg" readonly>
            <div class="flex flex-col justify-center items-center">
                <h1 class="mb-1">Register</h1>
                <div class="m-3 text-slate-300 border rounded">

                    <input class="p-1 rounded " type="text" name="fullname" aria-label="fullname" id="fullname" placeholder="Full Name">
                </div>
                <div class="m-3 text-slate-300 border rounded">

                    <input class="p-1 rounded" type="email" name="email" aria-label="email" id="email" placeholder="Email">
                </div>
                <p><span id="email-error" class="text-red-700 text-base p-none m-auto"></span></p>
                <div class="m-3 text-slate-300 border rounded">

                    <input class="p-1 rounded" type="password" name="pswrd1" aria-label="password" id="password" placeholder="Password">
                </div>
                <div class="m-3 text-slate-300 border rounded">
                    <input class="p-1 rounded" type="password" name="pswrd2" aria-label="password" id="password2" placeholder="Confirm Password">
                </div>
                <div class="m-3 text-slate-300">
                    <button type="submit" class="bg-blue-200 hover:bg-blue-400 text-slate-700 font-bold py-2 px-4 rounded">Create Account</button>

                </div>
                <div>
                    <p class="text-sm">Already have an account? <a href="../" class="text-blue-200 underline hover:no-underline">Sign in Here.</a></p>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.getElementById("email").addEventListener("change", function() {
            var Xreq = new XMLHttpRequest();
            Xreq.open("POST", "https://localhost/auth/db.php", true);
            Xreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            Xreq.send("r=emch&email=" + document.getElementById("email").value);
            if (Xreq.responseText == true) {
                document.getElementById("email-error").innerHTML = "Email already exists.";
            }
        });
        document.getElementById("password2").addEventListener("change", function() {
            var Xreq = new XMLHttpRequest();
            Xreq.open("POST", "https://localhost/auth/db.php", true);
            Xreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            Xreq.send("r=pswd&pswrd1=" + document.getElementById("password").value + "&pswrd2=" + document.getElementById("password2").value);
        });
    </script>
</body>

</html>