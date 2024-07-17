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

if(isset($_POST['email'])) {
    $newEmail = $_POST['email'];
    $oldEmail = $_SESSION['user_email'];

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);
    $get_usr = $conn->query("SELECT * FROM users WHERE email = '$oldEmail'");

    if($get_usr->num_rows > 0) {
        $update_usr = $conn->query("UPDATE users SET email = '$newEmail' WHERE email = '$oldEmail'");

        $_SESSION['user_email'] = $newEmail;

        header('Location: ../account?msg_email=Email updated');
        exit();
    } else {
        header('Location: ../account');
        exit();
    }
} else {
    header('Location: ../account');
    exit();
}

?>