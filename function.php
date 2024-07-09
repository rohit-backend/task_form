<!-- post methods to handle form submissions -->

<?php
session_start();
require_once("db_connection.php");
$conn = connect_database();
require_once("email_constants.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('phpMailer/vendor/autoload.php');

require_once("requirements.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $userName = $_POST["username"];

        empty_credentials_register($email, $password, $userName);
        validate_email($email);
        validate_password($password);
        check_password_register($password, $confirm_password);
        user_exist($conn, $email);

        // hashing the password
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO `users` VALUES (NULL, ?, ?, ?, current_timestamp());";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $userName, $email, $hash_password);
        $result = $stmt->execute();

        if ($result) {
            $sql_userid = "SELECT * FROM `users` ORDER BY `id` DESC LIMIT 1;";
            $result_user = $conn->query($sql_userid);
            $row = $result_user->fetch_assoc();

            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["name"];

            header("Location: dashboard.php");
            exit;
        }
    } else if (isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        empty_credentials_login($email, $password);
        validate_email_login($email);

        $sql = "SELECT * FROM `users` WHERE `email` = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // if user doesn't exist
        if ($result->num_rows == 0 || $result->num_rows == "0") {
            header("Location: login.php?err=user_not_exist");
            exit;
        }

        $rows = $result->fetch_assoc();

        // for verifying the password
        $actual_password = $rows["password"];
        $check_password = password_verify($password, $actual_password);
        if (!$check_password) {
            header("Location: login.php?err=incorrect_passwords");
            exit;
        }

        $_SESSION["user_id"] = $rows["id"];
        $_SESSION["user_name"] = $rows["name"];

        header("Location: dashboard.php");;
        exit;
    } else if (isset($_POST["remove_user"])) {
        $user_id = $_POST["user_id"];

        $sql = "DELETE FROM `users` WHERE `id` = $user_id;";
        $result = $conn->query($sql);

        if ($result) {
            echo ("user_removed");
            exit;
        } else {
            echo ('unexpected Server Error');
            exit;
        }
    } else if (isset($_POST["edit_user"])) {
        $user_id = $_POST["user_id"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($password == null || $password == "") {
            $sql = "UPDATE `users` SET `email` = ?, `name` = ? WHERE `id` = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $email, $username, $user_id);
            $result = $stmt->execute();

            if ($result) {
                echo ("user_edited");
                exit;
            }
        } else {
            $hash_password = password_hash($password, PASSWORD_BCRYPT);

            $sql = "UPDATE `users` SET `email` = ?, `name` = ?, `password`= ? WHERE `id` = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $email, $username, $hash_password, $user_id);
            $result = $stmt->execute();

            if ($result) {
                echo ("user_edited");
                exit;
            }
        }
    } else if (isset($_POST["forget_password_verify"])) {
        $email = $_POST["email"];

        validate_email_forgot_pass($conn, $email);

        // get user id
        $sql = "SELECT * FROM `users` WHERE `email` = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $user_id = $row["id"];

        $otp = sprintf('%04d', rand(0, 9999));
        $to = $email;
        $html = 'Your OTP is: <b>' . $otp . '</b>';
        $subject = "Verification OTP";

        $_SESSION["otp"] = $otp;
        $_SESSION["reset_pass_user_id"] = $user_id;

        function smtp_mailer($to, $subject, $html)
        {
            $mail = new PHPMailer();
            $mail->SMTPDebug  = 4;
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = MAIL_HOST;
            $mail->Port = 587;
            $mail->IsHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Username = SENDER_EMAIL;
            $mail->Password = SENDER_PASSWORD;
            $mail->SetFrom(SENDER_EMAIL, SENDER_NAME);
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->AddAddress($to);
            $mail->SMTPOptions = array('ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            ));

            $result = $mail->Send();
            return $result;
        }
        $mail = smtp_mailer($to, $subject, $html);

        if ($mail) {
            echo ('credentials_verified');
        } else {
            echo ('99');
        }
        exit;
    } else if (isset($_POST["verify_otp"])) {
        $otp = $_SESSION["otp"];
        $user_otp = $_POST["otp"];

        if ($otp != $user_otp) {
            echo ("wrong_otp");
            exit;
        } else {
            unset($_SESSION["otp"]);
            $_SESSION["user_reset_password"] = true;

            echo ("otp_verified");
            exit;
        }
    } else if (isset($_POST['reset_pass'])) {
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        empty_password($password);
        validate_password_reset($password);
        check_password_reset($password, $confirm_password);

        $user_id = $_SESSION["reset_pass_user_id"];
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "UPDATE `users` SET `password` = ? WHERE `id` = $user_id;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $hash_password);
        $result = $stmt->execute();

        if ($result) {
            header("Location: reset_password.php?err=pass_reset");
            exit;
        }
    }
} else {
    header("Location: index.php?err=illegal_access");
    exit;
}
