<?php
session_start();
include 'db.php';

// ඇඩ්මින් ලොග් වී නොමැති නම් ලොගින් පේජ් එකට යැවීම
if (!isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

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
    <title>Admin Core | AntusPlay PRO</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;500;800&display=swap" rel="stylesheet">
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
                <div class="welcome-text">
                    <h1>Control Panel</h1>
                    <p>Current Operator: <span class="glow-text"><?php echo $_SESSION['admin_user']; ?></span></p>
                </div>
                <div class="system-time" id="clock">00:00:00</div>
            </header>

            <section class="stats-grid">
                <div class="stat-card">
                    <h4>Total Customers</h4>
                    <h2 id="count-users"><?php echo $total_users; ?></h2>
                    <div class="progress-bar"><div class="fill" style="width: 75%;"></div></div>
                </div>
                
                <div class="stat-card">
                    <h4>Active Advertisements</h4>
                    <h2 id="count-ads"><?php echo $total_ads; ?></h2>
                    <div class="progress-bar"><div class="fill" style="width: 45%;"></div></div>
                </div>
                
                <div class="stat-card">
                    <h4>System Integrity</h4>
                    <h2 style="color: var(--primary);">99.9%</h2>
                    <div class="status-online">Operational</div>
                </div>
            </section>

            <div class="content-body" id="customer-section">
    <div class="section-header">
        <h3 class="section-title">Customer Intelligence</h3>
        <button class="btn-refresh" onclick="location.reload()">🔄 Sync Data</button>
    </div>
    
    <div class="glass-table-wrap">
        <table class="pro-table">
            <thead>
                <tr>
                    <th>Identifier</th>
                    <th>Customer Profile</th>
                    <th>Connectivity</th>
                    <th>Geography</th>
                    <th>Status</th>
                    <th>Onboarding</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM customers ORDER BY created_at DESC LIMIT 10";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $status_class = ($row['status'] == 'Active') ? 'status-active' : 'status-suspended';
                        $join_date = date('M d, Y', strtotime($row['created_at']));
                        
                        // JS Function එකට දත්ත යැවීම පහසු කිරීමට variables
                        $params = "'{$row['id']}', '{$row['full_name']}', '{$row['username']}', '{$row['email']}', '{$row['phone']}', '{$row['country']}', '{$row['status']}', '{$join_date}'";

                        echo "<tr>";
                        echo "<td><span class='id-badge'>#{$row['id']}</span></td>";
                        echo "<td>
                                <div class='user-profile-cell'>
                                    <div class='avatar-wrapper'>
                                        <img src='uploads/{$row['profile_pic']}' onerror=\"this.src='assets/img/default-user.png'\" alt=''>
                                        <div class='online-indicator'></div>
                                    </div>
                                    <div class='user-meta'>
                                        <span class='u-name'>{$row['full_name']}</span>
                                        <span class='u-handle'>@{$row['username']}</span>
                                    </div>
                                </div>
                              </td>";
                        echo "<td>
                                <div class='contact-cell'>
                                    <span>📧 {$row['email']}</span>
                                    <span class='u-phone'>📞 {$row['phone']}</span>
                                </div>
                              </td>";
                        echo "<td><span class='geo-tag'>📍 {$row['country']}</span></td>";
                        echo "<td><div class='status-pill {$status_class}'>{$row['status']}</div></td>";
                        echo "<td><span class='date-text'>{$join_date}</span></td>";
                        echo "<td>
                                <div class='action-bundle'>
                                    <button class='view-btn' onclick=\"showUserDetails({$params})\">👁️ View</button>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='no-data'>No operational data detected.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
        </main>
    </div>
    <div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <div id="modal-data">
            </div>
    </div>
    </div>
    <script src="assets/js/dashboard.js"></script>

    <script>
        // Page එක Load වන විට Animation එක ක්‍රියාත්මක කිරීම
        window.onload = () => {
            // PHP variables හරහා සජීවී දත්ත JS එකට ලබා දීම
            if(typeof animateValue === "function") {
                animateValue("count-users", 0, <?php echo $total_users; ?>, 2000);
                animateValue("count-ads", 0, <?php echo $total_ads; ?>, 2000);
            }
        };
    </script>
</body>
</html>