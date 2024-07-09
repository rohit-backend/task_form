<!-- this is the landing page of the user registration page -->
<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != false) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_SESSION["otp"])) {
    unset($_SESSION["otp"]);
}

if (isset($_SESSION["user_reset_password"])) {
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
            <div class="heading display-6 text-center my-2">Create Account</div>
            <form action="function.php" method="post" id="register_form">
                <input type="hidden" name="register" id="register" value="register">
                <div class="form-group my-4">
                    <label for="email">Enter Email</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="eg: user@gmail.com" />

                </div>

                <div class="form-group my-4">
                    <label for="username">Set Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="eg: your name" />
                </div>

                <div class="form-group my-4">
                    <label for="password">Set Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="must be of 8 characters" />
                </div>

                <div class="form-group my-4">
                    <label for="confirm-password">Re-type Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" />
                </div>
                <small id="ques" class="mt-lg-5 text-small"><a href="login.php" style="color:#333333;">Already have an account?<span class="login-ques" style="color:blue;">&nbsp;Login</span></a></small>
                <button type="submit" class="btn btn-info btn-sm float-right mt-3 my-4" id="submit_btn">Create</button>
            </form>
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
                    text: "Email is required for an account",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "empty_username") {
        ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Enter User-name!",
                    text: "User Name is required for an account",
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
                    text: "Password is required for an account",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "no_match_pass") {
        ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Passwords Don't match!",
                    text: "You entered incorrect password in Re-enter Password",
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
                    text: "Please enter a Valid email address",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "invalid_password") {
        ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Invalid Password!",
                    text: "Passwords must contain 8 characters",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "user_exist") {
        ?>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "User Exists!",
                    text: "Account Already exists please login!",
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