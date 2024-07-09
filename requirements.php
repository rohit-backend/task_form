<!-- all the required cases for form submission functions -->

<?php

function user_exist($conn, $email)
{
    $sql = "SELECT * FROM `users` WHERE `email` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: index.php?err=user_exist");
        exit;
    }
}
function empty_credentials_register($email, $password, $userName)
{
    if (empty($email) || empty($password) || empty($userName)) {
        if (empty($email)) {
            header("location: index.php?err=empty_email");
            exit;
        } else if (empty($userName)) {
            header("location: index.php?err=empty_username");
            exit;
        } else if (empty($password)) {
            header("location: index.php?err=empty_password");
            exit;
        }
    }
}

function empty_credentials_login($email, $password)
{
    if (empty($email) || empty($password)) {
        if (empty($email)) {
            header("location: login.php?err=empty_email");
            exit;
        } else if (empty($password)) {
            header("location: login.php?err=empty_password");
            exit;
        }
    }
}

function check_password_register($password, $confirm_password)
{
    if ($password !== $confirm_password) {
        header("Location: index.php?err=no_match_pass");
        exit;
    }
}

function validate_email($email)
{
    $trimEmail = trim($email);
    $check_email = filter_var($trimEmail, FILTER_VALIDATE_EMAIL);

    if (!$check_email) {
        header("Location: index.php?err=invalid_email");
        exit;
    }
}
function validate_email_login($email)
{
    $trimEmail = trim($email);
    $check_email = filter_var($trimEmail, FILTER_VALIDATE_EMAIL);

    if (!$check_email) {
        header("Location: login.php?err=invalid_email");
        exit;
    }
}

function validate_password($password)
{
    $length_pass = strlen($password);

    if ($length_pass < 8) {
        header("Location: index.php?err=invalid_password");
        exit;
    }
}

function validate_email_forgot_pass($conn, $email)
{
    $sql = "SELECT * FROM `users` WHERE `email` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0 || $result->num_rows == "0") {
        echo ("user_not_exist");
        exit;
    }
}

function empty_password($password)
{
    if (empty($password)) {
        header("Location: reset_password.php?err=empty_password");
        exit;
    }
}

function check_password_reset($password, $confirm_password)
{
    if ($password !== $confirm_password) {
        header("Location: reset_password.php?err=no_match_pass");
        exit;
    }
}

function validate_password_reset($password)
{
    $length_pass = strlen($password);

    if ($length_pass < 8) {
        header("Location: reset_password.php?err=invalid_password");
        exit;
    }
}
