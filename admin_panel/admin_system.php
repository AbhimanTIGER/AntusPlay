<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

// Analytics දත්ත ගැනීම
$total_views = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM site_analytics"))['total'];
$recent_logs = mysqli_query($conn, "SELECT * FROM security_logs ORDER BY created_at DESC LIMIT 10");

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
    <title>System Intelligence | AntusPlay</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/system.css"> <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo-area"><div class="logo-box">AP</div><h2>AntusPlay</h2></div>
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
                <h1 class="glitch-text" data-text="SYSTEM INTELLIGENCE">SYSTEM INTELLIGENCE</h1>
                <p>Real-time Network Traffic & Security Logs</p>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>TOTAL TRAFFIC</h3>
                    <div class="value"><?php echo $total_views; ?></div>
                    <p>Total Page Hits</p>
                </div>
            </div>

            <section class="log-zone">
                <h3 class="section-title">SECURITY EVENT LOGS</h3>
                <div class="log-container">
                    <?php while($log = mysqli_fetch_assoc($recent_logs)): ?>
                    <div class="log-entry">
                        <span class="log-time">[<?php echo date('H:i:s', strtotime($log['created_at'])); ?>]</span>
                        <span class="log-action"><?php echo $log['action']; ?></span> - 
                        <span class="log-details"><?php echo $log['details']; ?></span>
                        <span class="log-ip"><?php echo $log['ip_address']; ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>