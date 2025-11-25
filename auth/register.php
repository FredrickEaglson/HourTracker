<!DOCTYPE html>
<html>

<head>
    <title>Register | HourTracker</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <h1>Register</h1>
        <form class="" method="post">
            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
            <input type="text" class="form-control" id="email" name="email" placeholder="Email">
            
            <input type="text" class="form-control" id="username" name="username">
            <input type="password" class="form-control" id="password" name="password">
            <input type="password" class="form-control" id="password-cnfrm" name="password-cnfrm">
            <button type="submit" id="submit-btn" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script type="text/javascript">
        $("#username").onChange(function() {
            if (this.value.length >5) {
                $.get("reqs/get.php?type=1&username=" + this.value, function(data) {
                    if (data == "success") {
                        alert("Username already exists!");
                    }
                });
            }
        });






    </script>
</body>

</html>
<?php
use db\conn;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_cnfrm = $_POST['password-cnfrm'];
    
    if ($firstname == "" || $lastname == "" || $email == "" || $username == "" || $password == "" || $password_cnfrm == "") {
        echo "Please fill in all fields!";
    } else if ($password != $password_cnfrm) {
        echo "Passwords do not match!";
    } else  {
        
    }
}
?>