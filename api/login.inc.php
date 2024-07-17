<?php

require_once('db.inc.php');
session_start();

if(isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);
    $res_getusr = $conn->query("SELECT * FROM users WHERE email = '$email'");
    
    if($res_getusr->num_rows > 0) {
        $res_getusr = $res_getusr->fetch_assoc();

        $usr_pass = $res_getusr['password'];
        $is_pass_correct = password_verify($password, $usr_pass);

        if($is_pass_correct) {
            $get_todos = $conn->query("SELECT * FROM todos WHERE email = '$email'");
            $get_todos = $get_todos->fetch_assoc();

            $_SESSION['user_todos'] = json_decode($get_todos['todos_json'], true);

            $_SESSION['logged'] = true;
            $_SESSION['user_email'] = $email;

            header('Location: ../');
            exit();
        } else {
            header('Location: ../login?error=Incorrect password');
            exit();
        }
    } else {
        header('Location: ../login?error=User not found');
        exit();
    }
} else {
    header('Location: ../login');
    exit();
}

?>