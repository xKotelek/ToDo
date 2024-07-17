<?php

session_start();

$logged = false;
if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    $logged = true;
}

if(!$logged) {
    header('Location: ../login');
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
    <link rel="icon" href="../assets/logo.png">s
</head>
<body>
    <header class="head">
        <left>
            <name onclick="redirect('../');">ToDo!</name>
        </left>
        <centerr>
            <elementf class="selected"><t>Account</t></elementf>
        </centerr>
        <right>
            <login onclick="redirect('../api/logout.inc.php');">Logout</login>
        </right>
    </header>  
    <content class="content account">
        <center><h2>Your Account</h2></center>
        <contentgrid>
            <form class="content-item" action="../api/changeEmail.inc.php" method="POST">
                <center>
                <h3>Change Email</h3>
                <input class="authinp" type="email" name="email" placeholder="New Email" required><br>
                <input class="authinp submit" type="submit" value="Change Email">

                <?php if(isset($_GET['msg_email'])) { ?>
                <br><input class="authinp msg" type="submit" value="<?php echo $_GET['msg_email'] ?>">
                <?php } else if(isset($_GET['err_email'])) { ?>
                <br><input class="authinp err" type="submit" value="<?php echo $_GET['err_email'] ?>">
                <?php } ?>

                </center>
            </form>
            <form class="content-item" action="../api/resetPassword.inc.php" method="POST">
                <center>
                <h3>Reset Password</h3>
                <input class="authinp" type="password" name="password" placeholder="New Password" required><br>
                <input class="authinp" type="password" name="r-password" placeholder="Repeat Password" required><br>
                <input class="authinp submit" type="submit" value="Reset Password">

                <?php if(isset($_GET['msg_rpass'])) { ?>
                <br><input class="authinp msg" type="submit" value="<?php echo $_GET['msg_rpass'] ?>">
                <?php } else if(isset($_GET['err_rpass'])) { ?>
                <br><input class="authinp err" type="submit" value="<?php echo $_GET['err_rpass'] ?>">
                <?php } ?>

                </center>
            </form>
        </contentgrid>
    </content>
    <script src="../assets/script.js"></script>
</body>
</html>