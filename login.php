<?php
    include "connect.php";
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: home.php");
            exit();
        } else {
            echo "Thông tin đăng nhập sai";
        }
    }
?>

<form method="POST">
    <input type="text" name="username" placeholder="username" required>
    <input type="password" name="password" placeholder="password" required>
    <button type="submit">Đăng nhập</button>
    <button type="button"><a href="register.php">Đăng kí</a></button>
</form>
