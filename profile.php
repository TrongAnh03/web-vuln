<?php
    include "connect.php";
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn,$sql);
    $user = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $name = $_POST['name'];
        } else {
            $name = $user['name'];
        }
    
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $password = $user['password'];
        }

        if (isset($_POST['avatar_url']) && !empty($_POST['avatar_url'])) {
            $avatar_url = $_POST['avatar_url'];
        } else {
            $avatar_url = $user['avatar'];
        }

        if (strpos($avatar_url, '127.0.0.1') !== false || strpos($avatar_url, 'localhost') !== false) {
            echo "URL không hợp lệ.";
            exit();
        }

        $target_dir = "avatars/"; 
        $image_name = basename($avatar_url);
        $target_file = $target_dir.$image_name;

        if ($avatar_url !==  $user['avatar']) {
            $image_data = file_get_contents($avatar_url);
            file_put_contents($target_file, $image_data);
        } else {
            $target_file = $user['avatar'];
        }

        $sql_update = "UPDATE users SET name = '$name', password = '$password', avatar = '$target_file' WHERE id = $user_id";
        mysqli_query($conn, $sql_update);

        header("Location: profile.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h1>Cập nhật profile</h1>
    
    <form method="POST">
        <label for="name">Name:</label><br>
        <input type="text" name="name" value="<?php echo $user['name']; ?>"><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" name="password" placeholder="Mật khẩu mới"><br><br>
        
        <label for="avatar_url">Avatar</label><br>
        <input type="text" name="avatar_url" value="<?php echo $user['avatar']; ?>" placeholder="URL ảnh đại diện"><br><br>
        
        <button type="submit">Submit</button>
    </form>

    <h3>Current avatarr</h3>
    <img src="<?php echo $user['avatar']; ?>" width="100px" height="100px"><br><br>

    <a href="home.php">Quay lại home</a>
</body>
</html>