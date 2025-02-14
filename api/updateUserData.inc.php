<?php

require_once('db.inc.php');
session_start();

try {
    if(isset($_POST['todos_base64']) && isset($_POST['categories_base64'])) {
        $email = $_SESSION['user_email'];
        $todos_json = base64_decode($_POST['todos_base64']);
        $categories_json = base64_decode($_POST['categories_base64']);
    
        $conn = @new mysqli($host, $db_user, $db_password, $db_name);
        $check_user = $conn->query("SELECT * FROM todos WHERE email = '$email'");
    
        if($check_user->num_rows > 0) {
            $update_categories = $conn->query("UPDATE todos SET todos_json = '$todos_json', categories_json = '$categories_json' WHERE email = '$email'");
        }
    }

    header('Location: ../');
    exit();
} catch (Exception $e) {
    die("ERROR: " . $e);
}

?>