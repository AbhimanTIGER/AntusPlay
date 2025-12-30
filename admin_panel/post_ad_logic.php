<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Column නම් ඔයාගේ DB එකට ගැලපෙන විදිහට හැදුවා
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category_id = 0; // පසුව මෙය Category ID එකට සම්බන්ධ කරන්න පුළුවන්
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $tags = mysqli_real_escape_string($conn, $_POST['tags']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = 'Live';

    // Image logic
    $target_dir = "uploads/";
    $file_name = basename($_FILES["ad_image"]["name"]);
    $new_file_name = uniqid() . "_" . $file_name;
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($_FILES["ad_image"]["tmp_name"], $target_file)) {
        
        // ඔයාගේ Column names වලට අනුව SQL එක (title, description, tags, image_path, price)
        $sql = "INSERT INTO ads (title, description, tags, image_path, price, status) 
                VALUES ('$title', '$description', '$tags', '$new_file_name', '$price', '$status')";

        if (mysqli_query($conn, $sql)) {
            header("Location: manage_ads.php?status=success");
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "Image Upload Failed!";
    }
}
?>