<?php

session_start();

$_SESSION['logged'] = false;
$_SESSION['user_todos'] = null;
$_SESSION['user_email'] = null;

session_destroy();

header('Location: ../');
exit();

?>