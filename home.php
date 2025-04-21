<?php
include "connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    echo 'Xem trang cá nhân tại <a href="profile.php">profile</a></br>';
    echo 'Click vào đây để <a href="logout.php">logout</a></br>';
    echo 'Viết blog tại <a href="create_blog.php">đây</a>';
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, avatar FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$sql_blogs = "SELECT id, title, content, img FROM blogs WHERE user_id = $user_id";
$result_blogs = mysqli_query($conn, $sql_blogs);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
    </head>
    <body>
        <h1>Xin chào, <?php echo $user['name']; ?></h1>
        <h2>Blog của bạn</h2>

        <?php
        if (mysqli_num_rows($result_blogs) > 0) {
            while ($blog = mysqli_fetch_assoc($result_blogs)) {
                echo "<h3>" . $blog['title'] . "</h3>";
                echo "<p>" . $blog['content'] . "</p>";
                echo '<img src="' . $blog['img'] . '" width="100px" height="100px"><br><br>';

                echo '<a href="edit_blog.php?id=' . $blog['id'] . '">Chỉnh sửa</a> | ';
                echo '<a href="delete_blog.php?id=' . $blog['id'] . '">Xóa</a>';
            }
        } else {
            echo "Bạn chưa có blog nào. Hãy tạo cho mình blog đầu tiên.";
        }
        ?>
    </body>
</html>
