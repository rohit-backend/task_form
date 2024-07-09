$(document).ready(function () {
    $("#verification_form").show();
    $("#opt_form").hide();
});

$(document).ready(function () {
    $("#submit_ver_btn").on("click", function () {
        event.preventDefault();

        let form = document.getElementById('verification_form');
        let formData = new FormData(form);

        if (formData.get("email") == null || formData.get("email") == "") {
            Swal.fire({
                icon: "warning",
                title: "Email is required to reset Password!",
                showConfirmButton: true,
                confirmButtonText: "OK",
            });
            return;
        }

        // to verify email
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+/;
            return emailRegex.test(email);
        }

        let userEmail = formData.get("email");
        if (!validateEmail(userEmail) || validateEmail(userEmail) == false) {
            Swal.fire({
                icon: "warning",
                title: "Please enter a valid Email",
                showConfirmButton: true,
                confirmButtonText: "OK",
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: '/PHP/innovins_task/function.php',
            data: $('#verification_form').serialize(),
            success: function (data) {
                if (data.includes("user_not_exist")) {
                    Swal.fire({
                        icon: "warning",
                        title: "User Not Found!",
                        text: "Please Enter the registered email or Create an Account.",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                    return;
                } else if (data.includes("credentials_verified")) {
                    Swal.fire({
                        icon: "success",
                        title: "Credentials Verified",
                        text: "A Four digit OTP has been send on your email.",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#verification_form").hide();
                            $("#opt_form").show();
                        }
                    });;
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Server Error",
                        text: "Internal Server Error \n" + data,
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                    return;
                }
            }
        });
    });

    $("#verify_otp_submit").on("click", function () {
        event.preventDefault();

        let form = document.getElementById('opt_form');
        let formData = new FormData(form);


        if (formData.get("otp") == null || formData.get("otp") == "") {
            Swal.fire({
                icon: "warning",
                title: "Enter the OTP",
                text: "Enter the four-digit OTP send on your email.",
                showConfirmButton: true,
                confirmButtonText: "OK",
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: '/PHP/innovins_task/function.php',
            data: $('#opt_form').serialize(),
            success: function (data) {
                if (data.includes("wrong_otp")) {
                    Swal.fire({
                        icon: "error",
                        title: "Wrong OTP!",
                        text: "Enter the four-digit OTP send on your email.",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                    return;
                } else if (data.includes("otp_verified")) {
                    Swal.fire({
                        icon: "success",
                        title: "OTP Verified!",
                        text: "Now, You can Reset Your Password",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "/PHP/innovins_task/reset_password.php";
                        }
                    });
                }
            }
        });
    });

});