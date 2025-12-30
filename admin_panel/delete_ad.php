<?php
session_start();
include 'db.php';

// Security: ලොග් වෙලා නැත්නම් කරන්න දෙන්න එපා
if (!isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. මුලින්ම පින්තූරයේ නම හොයාගන්න (ෆෝල්ඩර් එකෙන් මකන්න)
    $res = mysqli_query($conn, "SELECT image_path FROM ads WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    $image_name = $row['image_path'];

    // 2. Database එකෙන් දත්තය මකන්න
    $delete_query = "DELETE FROM ads WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        // 3. පින්තූරය uploads ෆෝල්ඩර් එකෙනුත් මකන්න
        if (file_exists("uploads/" . $image_name)) {
            unlink("uploads/" . $image_name);
        }
        echo "<script>alert('Ad deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
