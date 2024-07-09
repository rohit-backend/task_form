<?php
session_start();

if (isset($_SESSION["otp"])) {
    header("Location: forgot_password.php?err=illegal_acc");
    exit;
}

if (!isset($_SESSION["user_reset_password"])) {
    header("Location: forgot_password.php?err=illegal_acc");
    exit;
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
            <form action="function.php" method="post" id="reset_pass_form">
                <input type="hidden" name="reset_pass" value="reset_pass">
                <div class="form-group my-4">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="must be of 8 characters" />
                </div>

                <div class="form-group my-4">
                    <label for="confirm-password">Re-type Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" />
                </div>

                <div style="display: flex; justify-content: flex-end !important;">
                    <button type="submit" class="btn btn-success btn-sm float-right mt-3 my-4" id="verify_otp_submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    require_once("footer.php");
    ?>

    <?php
    if (isset($_GET["err"])) {
        if ($_GET["err"] == "empty_password") {
    ?>
            <script>
                Swal.fire({

                    icon: "warning",
                    title: "Enter Password!",
                    text: "Password is required",
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

                    icon: "warning",
                    title: "Passwords Don't match!",
                    text: "You entered incorrect password in Re-enter Password",
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

                    icon: "warning",
                    title: "Invalid Password!",
                    text: "Passwords must contain 8 characters",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            </script>
        <?php
            exit;
        } else if ($_GET["err"] == "pass_reset") {
            if (isset($_SESSION["user_reset_password"])) {
                unset($_SESSION["user_reset_password"]);
                unset($_SESSION["reset_pass_user_id"]);
            }
        ?>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Password Changed",
                    text: "Now, Login with your new Password!",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "login.php";
                    }
                });
            </script>
    <?php
            exit;
        }
    }
    ?>
</body>

</html>