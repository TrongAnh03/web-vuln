<?php
    include "connect.php";
    if (isset($_SESSION['username']) and isset($_SESSION['password'])) {
        header("location:home.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Trang chu</title>
    </head>
    <body>
        <div>
            <a href="login.php">Login</a>
        </div>
        <div>
            <a href="register.php">Register</a>
        </div>
    </body>
</html>