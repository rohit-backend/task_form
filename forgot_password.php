<?php
session_start();

if (isset($_SESSION["otp"]) && $_SESSION["otp"] != false) {
    unset($_SESSION["otp"]);
}

if (isset($_SESSION["reset_pass_user_id"]) && $_SESSION["reset_pass_user_id"] != false) {
    unset($_SESSION["reset_pass_user_id"]);
}

if (isset($_SESSION["user_reset_password"]) && $_SESSION["user_reset_password"] != false) {
    unset($_SESSION["user_reset_password"]);
}


require_once("header.php");
?>

<link rel="stylesheet" href="css/register_login.css">

<body>

    <div class="container">
        <h1 class="display-6 text-dark text-center my-3 shadow">
            Task 2 - User Autentication and access of a specific page
        </h1>

        <div class="form my-4">
            <div class="heading display-6 text-center my-2">Reset Password</div>

            <!-- verification form -->
            <form id="verification_form">
                <input type="hidden" name="forget_password_verify" id="forget_password_verify">
                <div class="form-group my-4">
                    <label for="email">Enter Email</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="eg: user@gmail.com" />
                </div>

                <div style="display: flex; justify-content: flex-end !important;">
                    <button type="button" class="btn btn-success btn-sm float-right mt-3" id="submit_ver_btn">Send OTP</button>
                </div>
            </form>


            <!-- OTP Form -->
            <form id="opt_form">
                <input type="hidden" name="verify_otp" id="verify_otp" value="verify_otp">
                <div class="form-group my-4">
                    <label for="email">Enter OTP</label>
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter 4-digit OTP send on the email" />
                </div>

                <div style="display: flex; justify-content: flex-end !important;">
                    <button type="button" class="btn btn-success btn-sm float-right mt-3 my-4" id="verify_otp_submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    require_once("footer.php");
    ?>\
    <script src="js/forgot_password.js"></script>
</body>

</html>