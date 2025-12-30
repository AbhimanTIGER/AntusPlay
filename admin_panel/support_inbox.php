<?php
session_start();
include 'db.php';

// මැසේජ් එකක් 'Read' කළාම Status එක වෙනස් කරන්න
if(isset($_GET['mark_read'])){
    $id = $_GET['mark_read'];
    mysqli_query($conn, "UPDATE support_messages SET status='read' WHERE id=$id");
}

$messages = mysqli_query($conn, "SELECT * FROM support_messages ORDER BY created_at DESC");

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
    <title>Support Inbox | AntusPlay</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .msg-card { background: #111; border-left: 4px solid #00ff41; padding: 15px; margin-bottom: 10px; }
        .unread { border-left-color: #ff4b4b; background: #1a0000; }
        .msg-meta { font-size: 11px; color: #888; margin-bottom: 5px; }
        .msg-body { color: #eee; font-size: 14px; }
    </style>
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
        <h1 class="glitch-text">SUPPORT INBOX</h1>
        <div class="inbox-container">
            <?php while($row = mysqli_fetch_assoc($messages)): ?>
                <div class="msg-card <?php echo $row['status']; ?>">
                    <div class="msg-meta">
                        FROM: <?php echo $row['name']; ?> (<?php echo $row['ip_address']; ?>) | TIME: <?php echo $row['created_at']; ?>
                    </div>
                    <div class="msg-body">
                        <strong>Subject: <?php echo $row['subject']; ?></strong><br>
                        <?php echo $row['message']; ?>
                    </div>
                    <?php if($row['status'] == 'unread'): ?>
                        <a href="?mark_read=<?php echo $row['id']; ?>" style="color:#00ff41; font-size:12px;">Mark as Read</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>