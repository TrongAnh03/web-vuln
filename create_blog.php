<?php
include "connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title']; 
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['image'])) {
        $target_dir = "uploads/";
        $image_name = $_FILES["image"]["name"];
        $target_file = $target_dir . $image_name;
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES["image"]["tmp_name"]);
        finfo_close($finfo);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($mime_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO blogs (user_id, title, content, img) VALUES ($user_id, '$title', '$content', '$target_file')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    header("Location: home.php");
                    exit();
                } else {
                    echo "Có lỗi xảy ra khi lưu blog.";
                }
            } else {
                echo "Có lỗi xảy ra khi upload file.";
            }
        } else {
            echo "File không hợp lệ. Vui lòng upload các file ảnh (JPEG, PNG, GIF).";
        }
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Tiêu đề" required></br>
    <textarea name="content" placeholder="Nội dung" required></textarea></br>
    <input type="file" name="image"></br>
    <button type="submit">Tạo blog</button>
</form>
