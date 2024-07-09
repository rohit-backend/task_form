<!-- this is the login page if user already have an account -->
<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != false) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_SESSION["otp"]) && $_SESSION["otp"] != false) {
    unset($_SESSION["otp"]);
}

if (isset($_SESSION["user_reset_password"]) && $_SESSION["user_reset_password"] != false) {
    unset($_SESSION["user_reset_password"]);
}

if (isset($_SESSION["reset_pass_user_id"])) {
    unset($_SESSION["reset_pass_user_id"]);
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
            <div class="heading display-6 text-center my-2">Login</div>
            <form action="function.php" method="post" id="login_form">
                <input type="hidden" name="login" id="login" value="login">
                <div class="form-group my-4">
                    <label for="email">Enter Email</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="eg: user@gmail.com" />
                </div>

                <div class="form-group my-4">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="must be of 8 characters" />
                </div>

                <small id="ques" class="mt-lg-5 text-small"><a href="index.php" style="color:#333333;">New Here?<span style="color:blue;">&nbsp;Register</span></a></small>
                <button type="submit" class="btn btn-success btn-sm float-right mt-3 my-4" id="submit_btn">Login</button>
            </form>

            <!-- forgot password -->
            <div class="forgot_password mt-5 float-right">
                <small id="ques" class="text-small mt-1 mx-0 px-0">Forgot Password?</small>
                <a href="forgot_password.php">
                    <span style="color: blue;"><button class="btn btn-sm btn-transparent mx-0 px-0">Reset Password</button>
                    </span>
                </a>

            </div>
        </div>
    </div>

    <?php
    require_once("footer.php");
    ?>

    <?php
    if (isset($_GET["err"])) {
        if ($_GET["err"] == "empty_email") {
    ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Enter Email!",
                    text: "Email is required to login",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "empty_password") {
        ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Enter Password!",
                    text: "Password is required to login",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "invalid_email") {
        ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Invalid Email!",
                    text: "Please Enter a Valid Email",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "user_not_exist") {
        ?>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Invalid Credentials!",
                    text: "Please Enter the registered email or Create an Account.",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "incorrect_passwords") {
        ?>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Invalid Credentials!",
                    text: "Incorrect Email address or Password.",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
    <?php
            exit;
        }
    }
    ?>
</body>

</html>