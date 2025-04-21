<?php
    include "connect.php";

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];

        $check_exist_username_query = "SELECT * FROM users WHERE username='$username'";
        $check_exist_username_result = mysqli_query($conn, $check_exist_username_query);
        if (mysqli_num_rows($check_exist_username_result) > 0) {
            echo "username đã tồn tại. Vui lòng nhập username khác";
        } else {
            $insert_sql = "INSERT INTO users (username, password, name) VALUES ('$username', '$password', '$name')";
            $insert_result = mysqli_query($conn, $insert_sql);
        
            if($insert_result){
                echo "Đăng kí thành công!";
                header("Location: login.php");
            } else {
                echo "Đăng kí thất bại!";
            }
        }
    }
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="name" placeholder="name" required>
    <button type="submit">Đăng ký</button>
    <button type="button"><a href="login.php">Đăng nhập</a></button>
</form>