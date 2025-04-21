<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$blog_id = $_GET['id'];

$sql_check = "SELECT * FROM blogs WHERE id = $blog_id"; 
$result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) > 0) {
    $sql = "DELETE FROM blogs WHERE id = $blog_id"; 
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: home.php"); 
        exit();
    } else {
        echo "Xóa blog chưa thành công"; 
    }
} else {
    echo "Blog không tồn tại";
}
?>
