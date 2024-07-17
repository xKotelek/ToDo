<?php

session_start();
require_once('db.inc.php');

$logged = false;
if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    $logged = true;
}

if(!$logged) {
    header('Location: ../login');
    exit();
}

if(isset($_POST['password']) && isset($_POST['r-password'])) {
    $password = $_POST['password'];
    $rpassword = $_POST['r-password'];
    $email = $_SESSION['user_email'];

    $resetToken = bin2hex(random_bytes(32));

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);
    $get_usr = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if($get_usr->num_rows > 0) {
        if($password == $rpassword) {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $update_temp_pass = $conn->query("UPDATE users SET newPassword = '$password', resetPasswordToken = '$resetToken' WHERE email = '$email'");

            require("PHPMailer/src/PHPMailer.php");
            require("PHPMailer/src/SMTP.php");
            require("PHPMailer/src/Exception.php");
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->IsSMTP();
            $mail->CharSet="UTF-8";

            $mail->Host = "s1.example.com";

            $mail->SMTPDebug = 0;
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->IsHTML(true);

            $mail->Username = "noreply@example.com";
            $mail->Password = "ExampleNoreply.";
            $mail->setFrom('noreply@example.com', 'ToDo • Password Recovery');

            $mail->AddAddress($email);
            $mail->Subject = "ToDo • Password Recovery";

            $email = urlencode($email);
            $token = urlencode($resetToken);

            $html = '
            <div style="background:#1b263b;width:100%;height:100vh;">
                <center style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);padding:30px;border-radius:10px;background:#0d1b2a;">
                    <h1 style="color:white;font-weight:bold;">ToDo!</h1><br>
                    <p style="color:#ffffff50;font-size:16px;">Click the button below to confirm your password reset</p>
                    <a style="font-size:18px;color:white;padding:10px;border-radius:10px;cursor:pointer;background:#72727215;border: 1px solid #ffffff10;" href="http://localhost/todo/resetPassword/?email='.$email.'&token='.$token.'" target="_blank">Confirm Reset</a>
                </center>
            </div>
            ';
            $mail->Body = $html;

            if($mail->send()) {
                header('Location: ../account/?msg_rpass=Confirmation email sent');
                exit();
            } else {
                header('Location:../account/?err_rpass=Failed to send password reset email');
                exit();
            }        
        } else {
            header('Location: ../account/?err_rpass=Passwords don\'t match');
            exit();
        }
    } else {
        header('Location: ../account');
        exit();
    }
} else {
    header('Location: ../account');
    exit();
}

?>