<?php

session_start();
require_once('../api/db.inc.php');

if(isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);
    $get_usr = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if($get_usr->num_rows > 0) {
        $get_usr = $get_usr->fetch_assoc();

        if($get_usr['resetPasswordToken'] == $token) {
            $newPass = $get_usr['newPassword'];
            $update_pass = $conn->query("UPDATE users SET password = '$newPass', newPassword = '', resetPasswordToken = '' WHERE email = '$email'");

            $success = true;
            $msg = "Password has been changed.";
        } else {
            $success = false;
            $msg = "Invalid password reset token.";
        }
    } else {
        $success = false;
        $msg = "Invalid data provided.";
    }
} else {
    $success = false;
    $msg = "Invalid data provided.";
}

?>

<head><title>ToDo &bullet; Password Reset</title><link rel="icon" href="assets/logo.png"></head>
<div style="background:#1b263b;width:100%;height:100vh;">
    <center style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);padding:30px;border-radius:10px;background:#0d1b2a;">
        <h1 style="color:white;font-weight:bold;">ToDo!</h1>
        <p style="<?php if($success) { ?>color:#00ff0075;<?php } else { ?>color:#ff000075;<?php } ?>font-size:18px;"><?php echo $msg; ?></p><br>
        <a style="text-decoration:none;font-size:16px;color:white;padding:10px;border-radius:10px;cursor:pointer;background:#72727215;border: 1px solid #ffffff10;" href="#" onclick="window.close();return false;">Close this tab</a>
    </center>
</div>