<?php

session_start();
require_once('db.inc.php');

if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['r-password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rpassword = $_POST['r-password'];

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);
    $check_if_exist = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if($check_if_exist->num_rows == 0) {
        if($password == $rpassword) {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $register_usr = $conn->query("INSERT INTO users (email, password, newPassword, resetPasswordToken) VALUES ('$email', '$hashed_pass', '', '')");
            $add_empty_todos = $conn->query("INSERT INTO todos (email, todos_json, categories_json) VALUES ('$email', '{\"todos\":[]}', '{\"categories\":[{\"name\":\"Example Category\", \"token\": \"RXhhbXBsZSBDYXRlZ29yeSw0eWRtNmprUW1OdTh6bWpYRkpWUmh6ZmxJbUZVMng2aw==\", \"randomString\": \"4ydm6jkQmNu8zmjXFJVRhzflImFU2x6k\", \"todos\":[]}]}')");
            
            $_SESSSION['user_todos'] = array(
                "todos" => array()
            );

            $_SESSION['logged'] = true;
            $_SESSION['user_email'] = true;
            
            header('Location: ../');
            exit();
        } else {
            header('Location: ../register?error=Password don\'t match');
            exit();
        }
    } else {
        header('Location: ../register?error=User already exists');
        exit();
    }
} else {
    header('Location: ../register');
    exit();
}

?>