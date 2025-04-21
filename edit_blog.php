<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$blog_id = $_GET['id'];

$sql = "SELECT * FROM blogs WHERE id = '$blog_id'";
$result = mysqli_query($conn, $sql);
$blog = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_title = $blog['title'];
    $current_content = $blog['content'];
    $current_img = $blog['img'];

    if (!empty($_POST['title'])) {
        $title = $_POST['title'];
    } else {
        $title = $current_title;
    }

    if (!empty($_POST['content'])) {
        $content = $_POST['content'];
    } else {
        $content = $current_content;
    }

    if (isset($_FILES['image'])) {
        $target_dir = "uploads/";
        $image_name = $_FILES["image"]["name"];
        $target_file = $target_dir . $image_name;

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES["image"]["tmp_name"]);

        if (strpos($mime_type, 'image/') === 0) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $img = $target_file;
        } else {
            echo "Tệp không phải là hình ảnh.";
            exit();
        }
        
        finfo_close($file_info);
    } else {
        $img = $current_img; 
    }

    $sql = "UPDATE blogs SET title = '$title', content = '$content', img = '$img' WHERE id = '$blog_id'";
    mysqli_query($conn, $sql);

    header("Location: home.php");
    exit();
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?php echo $blog['title']; ?>" placeholder="Tiêu đề mới">
    <textarea name="content" placeholder="Nội dung mới"><?php echo $blog['content']; ?></textarea>
    <input type="file" name="image">
    <button type="submit">Cập nhật blog</button>
</form>
