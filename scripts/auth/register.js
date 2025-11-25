$(document).ready(function() {
    $(".step2").hide();
    $("#next-btn").click(function() {
        var firstname = $("#firstname").val();
        var lastname = $("#lastname").val();
        var email = $("#email").val();
        if (firstname == "" || lastname == "" || email == "") {
            alert("Please fill in all fields!");
        } else {
            $.ajax({
                type: "POST",
                url: "auth/register.php",
                data: {
                    firstname: firstname,
                    lastname: lastname,
                    email: email
                },
                success: function(data) {
                    if (data == "success") {
                        $(".step1").hide();
                        $(".step2").show();
                    } else {
                        alert("Registration failed!");
                    }
                }
            });
        }
    });
});