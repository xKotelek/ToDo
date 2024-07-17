<?php

require_once('db.inc.php');
session_start();

if(isset($_GET['categories_json'])) {
    $email = $_SESSION['user_email'];
    $categories_json = base64_decode($_GET['categories_json']);

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);
    $check_user = $conn->query("SELECT * FROM todos WHERE email = '$email'");

    if($check_user->num_rows > 0) {
        $update_categories = $conn->query("UPDATE todos SET categories_json = '$categories_json' WHERE email = '$email'");
    }
}

header('Location: ../');
exit();

?>