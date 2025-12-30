<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

// Update Settings Logic
if(isset($_POST['update_settings'])){
    $name = mysqli_real_escape_string($conn, $_POST['site_name']);
    $email = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $comm = $_POST['comm_rate'];
    $maint = isset($_POST['maint_mode']) ? 1 : 0;

    mysqli_query($conn, "UPDATE system_settings SET 
        site_name='$name', 
        contact_email='$email', 
        affiliate_commission_rate='$comm', 
        maintenance_mode='$maint' 
        WHERE id=1");
    
    // Security Log එකක් දාමු
    mysqli_query($conn, "INSERT INTO security_logs (action, user_involved, details, ip_address) 
                         VALUES ('SETTINGS_UPDATE', 'admin', 'System configuration changed', '".$_SERVER['REMOTE_ADDR']."')");
    
    header("Location: system_settings.php?status=updated");
}

$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM system_settings WHERE id=1"));
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
    <title>Core Engine Settings | AntusPlay</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .config-box { background: rgba(0, 255, 65, 0.03); border: 1px solid #333; padding: 20px; border-radius: 5px; }
        .config-box h3 { color: #00ff41; font-family: 'Orbitron'; font-size: 13px; margin-bottom: 15px; }
        input[type="text"], input[type="number"], input[type="email"] {
            width: 100%; background: #000; border: 1px solid #444; color: #fff; padding: 10px; margin-top: 5px; border-radius: 3px;
        }
        .toggle-switch { display: flex; align-items: center; gap: 10px; color: #fff; }
        .save-btn { background: #00ff41; color: #000; border: none; padding: 12px 30px; font-family: 'Orbitron'; font-weight: bold; cursor: pointer; margin-top: 20px; }
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
    <div class="header-area" style="margin-bottom: 30px;">
        <h1 class="glitch-text">CORE CONFIGURATION</h1>
        <p style="color: #888;">Manage global system protocols and financial rates.</p>
    </div>

    <form action="" method="POST">
        <div class="settings-grid">
            <div class="config-box">
                <h3>SYSTEM IDENTITY</h3>
                <div class="input-group">
                    <label>Platform Name</label>
                    <input type="text" name="site_name" value="<?php echo $settings['site_name']; ?>">
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <label>Master Email</label>
                    <input type="email" name="contact_email" value="<?php echo $settings['contact_email']; ?>">
                </div>
            </div>

            <div class="config-box">
                <h3>FINANCIAL ENGINE</h3>
                <label>Commission Rate (%)</label>
                <input type="number" step="0.01" name="comm_rate" value="<?php echo $settings['affiliate_commission_rate']; ?>">
                
                <div class="maint-wrapper" style="margin-top: 30px; display:flex; align-items:center; gap:15px;">
                    <input type="checkbox" id="maint" name="maint_mode" <?php echo $settings['maintenance_mode'] ? 'checked' : ''; ?>>
                    <label for="maint" style="cursor:pointer;">Emergency Maintenance Mode</label>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" name="update_settings" class="save-btn">APPLY PROTOCOLS</button>
        </div>
    </form>
</main>
</body>
</html>