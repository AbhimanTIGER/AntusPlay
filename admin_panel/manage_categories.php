<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

// 1. Add Category Logic
if (isset($_POST['add_category'])) {
    $cat_name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    if (!empty($cat_name)) {
        mysqli_query($conn, "INSERT INTO categories (category_name) VALUES ('$cat_name')");
        header("Location: manage_categories.php?status=added");
        exit();
    }
}

// 2. Delete Category Logic
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    header("Location: manage_categories.php?status=deleted");
    exit();
}

// මුළු Categories ගණන ගැනීම
$c_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories");
$total_categories = mysqli_fetch_assoc($c_res)['total'];


// 1. Dashboard එකට අවශ්‍ය සංඛ්‍යාත්මක දත්ත (Stats) ලබා ගැනීම
$total_users = 0; 
$total_ads = 0; 
$total_categories = 0;

// මුළු පාරිභෝගිකයින් ගණන
$user_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM customers");
if($user_res) { 
    $user_data = mysqli_fetch_assoc($user_res); 
    $total_users = $user_data['total']; 
}

// මුළු දැන්වීම් ගණන
$ads_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM ads");
if($ads_res) { 
    $ads_data = mysqli_fetch_assoc($ads_res); 
    $total_ads = $ads_data['total']; 
}

// මුළු කැටගරි ගණන (Sidebar එකේ පෙන්වන්න පුළුවන්)
$cat_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories");
if($cat_res) { 
    $cat_data = mysqli_fetch_assoc($cat_res); 
    $total_categories = $cat_data['total']; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sector Management | AntusPlay</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/categories.css"> <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo-area">
                <div class="logo-box">AP</div>
                <h2>AntusPlay</h2>
            </div>
            <nav class="side-nav">
                <a href="admin_dashboard.php" class="nav-link active"><span>🏠</span> Overview Customers (<?php echo $total_users; ?>)</a>
                <a href="manage_ads.php" class="nav-link"><span>📢</span> Manage Ads (<?php echo $total_ads; ?>)</a>
                <a href="manage_categories.php" class="nav-link"><span>📁</span> Categories (<?php echo $total_categories; ?>)</a>
                <a href="#" class="nav-link"><span>💰</span> Financials</a>
                <a href="admin_system.php" class="nav-link"><span>🛡️</span>Logs & 📊 Analytics</a>
                <a href="support_inbox.php" class="nav-link"><span>✉️</span> Support Inbox</a>
                <a href="system_settings.php" class="nav-link"><span>⚙️</span> System Settings</a>

                <a href="logout.php" class="nav-link logout-btn"><span>🚀</span> Terminate Session</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div class="header-info">
                    <h1 class="glitch-text" data-text="SECTOR CONTROL">SECTOR CONTROL</h1>
                    <p>Database Classification Management</p>
                </div>
            </header>

            <div class="category-wrapper">
                <section class="add-sector-zone">
                    <div class="cyber-card">
                        <h3>INITIALIZE NEW SECTOR</h3>
                        <form action="" method="POST" class="inline-form">
                            <input type="text" name="cat_name" placeholder="Enter Sector Name (e.g. Laptops)" required>
                            <button type="submit" name="add_category" class="execute-btn">ADD SECTOR</button>
                        </form>
                    </div>
                </section>

                <section class="table-zone">
                    <div class="glass-table-container">
                        <table class="cyber-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>SECTOR NAME</th>
                                    <th>CREATED AT</th>
                                    <th>CONTROL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
                                if (mysqli_num_rows($cats) > 0) {
                                    while ($row = mysqli_fetch_assoc($cats)) {
                                        echo "<tr>
                                            <td>#{$row['id']}</td>
                                            <td class='cat-name'>{$row['category_name']}</td>
                                            <td>{$row['created_at']}</td>
                                            <td>
                                                <a href='manage_categories.php?delete={$row['id']}' 
                                                   class='terminate-btn' 
                                                   onclick='return confirm(\"Are you sure you want to delete this sector?\")'>
                                                   REMOVE
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' style='text-align:center;'>No sectors initialized in database.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>