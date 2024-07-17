<?php

require_once('db.inc.php');
session_start();

if(isset($_POST['todos_base64'])) {
    $email = $_SESSION['user_email'];
    $todos_json = base64_decode($_POST['todos_base64']);

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);
    $check_user = $conn->query("SELECT * FROM todos WHERE email = '$email'");

    if($check_user->num_rows > 0) {
        $update_todos = $conn->query("UPDATE todos SET todos_json = '$todos_json' WHERE email = '$email'");
    }
}

header('Location: ../');
exit();

?>