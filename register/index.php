<?php

session_start();

if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    header('Location: ../');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="icon" href="../assets/logo.png">
</head>
<body>
    <header class="head">
        <left>
            <name onclick="redirect('../');">ToDo!</name>
        </left>
        <centerr>
            <elementf onclick="redirect('../login');"><t>Login</t></elementf>
            <elementf class="selected"><t>Register</t></elementf>
        </centerr>
    </header>  
    <content class="content auth">
        <center>
        <form class="authform" method="POST" action="../api/register.inc.php">
            <h1>Register</h1>
            <input class="authinp" type="email" name="email" placeholder="Email" required><br>
            <input class="authinp" type="password" name="password" placeholder="Password" required><br>
            <input class="authinp" type="password" name="r-password" placeholder="Repeat Password" required><br>
            <input class="authinp submit" type="submit" value="Register">
            
            <?php if(isset($_GET['error'])) { ?>
            <br><input class="authinp err" type="submit" value="<?php echo $_GET['error'] ?>">
            <?php } ?>
        </form>
        </center>
    </content>
    <script src="../assets/script.js"></script>
</body>
</html>