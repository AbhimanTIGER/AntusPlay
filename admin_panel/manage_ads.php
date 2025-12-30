<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

// Counts ලබා ගැනීම
$total_users = 0; $total_ads = 0; $total_categories = 0;
$u_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM customers");
if($u_res) { $total_users = mysqli_fetch_assoc($u_res)['total']; }

$a_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM ads");
if($a_res) { $total_ads = mysqli_fetch_assoc($a_res)['total']; }

$c_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories");
if($c_res) { $total_categories = mysqli_fetch_assoc($c_res)['total']; }

// Delete Logic
if(isset($_GET['delete'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $img_res = mysqli_query($conn, "SELECT image_path FROM ads WHERE id = $id");
    $img_data = mysqli_fetch_assoc($img_res);
    if($img_data && !empty($img_data['image_path']) && file_exists("uploads/" . $img_data['image_path'])){
        unlink("uploads/" . $img_data['image_path']);
    }
    mysqli_query($conn, "DELETE FROM ads WHERE id = $id");
    header("Location: manage_ads.php?status=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Global Inventory Control | AntusPlay</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/manage_ads.css">
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
                <div class="header-info">
                    <h1 class="glitch-text" data-text="INVENTORY CONTROL">INVENTORY CONTROL</h1>
                    <p>Sub-orbital Asset Management System</p>
                </div>
                <button class="cyber-btn" id="openFormBtn">NEW DEPLOYMENT +</button>
            </header>

            <?php if(isset($_GET['status'])): ?>
                <div class="status-msg">
                    <?php echo ($_GET['status'] == 'success') ? "✅ ASSET DEPLOYED" : "🗑️ ASSET TERMINATED"; ?>
                </div>
            <?php endif; ?>

            <section class="deployment-zone" id="adFormContainer" style="display: none;">
                <div class="cyber-terminal">
                    <div class="terminal-header">
                        <span class="pulse-dot"></span>
                        <h2 class="terminal-title">ASSET DEPLOYMENT MODULE v2.0</h2>
                    </div>
                    
                    <form action="post_ad_logic.php" method="POST" enctype="multipart/form-data" class="terminal-form">
                        <div class="form-row">
                            <div class="input-box">
                                <label>CORE DESIGNATION (Title)</label>
                                <input type="text" name="title" placeholder="Identify asset..." required>
                            </div>
                            <div class="input-box" style="position: relative;">
                                <label>SECTOR CLASSIFICATION (Search Category)</label>
                                <input type="text" id="categorySearch" name="category" placeholder="Type to search..." autocomplete="off" onkeyup="searchCategory(this.value)" required>
                                <div id="suggestionBox" class="suggestion-box"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-box">
                                <label>MARKET VALUATION (LKR)</label>
                                <input type="number" name="price" placeholder="0.00" required>
                            </div>
                            <div class="input-box">
                                <label>AI SEARCH TAGS</label>
                                <input type="text" name="tags" placeholder="iphone, apple, 5g..." required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-box">
                                <label>ASSET CONDITION</label>
                                <select name="condition">
                                    <option value="New">Brand New</option>
                                    <option value="Used">Used</option>
                                </select>
                            </div>
                            <div class="input-box">
                                <label>VISUAL CORE</label>
                                <label for="fileInput" class="custom-file-upload" id="fileLabel">SELECT IMAGE</label>
                                <input type="file" name="ad_image" id="fileInput" hidden required>
                            </div>
                        </div>

                        <div class="input-box full-width">
                            <label>MISSION SPECIFICATIONS</label>
                            <textarea name="description" rows="3" placeholder="Technical details..."></textarea>
                        </div>

                        <div class="terminal-actions">
                            <button type="submit" class="execute-btn">EXECUTE DEPLOYMENT</button>
                            <button type="button" class="abort-btn" id="closeFormBtn">ABORT</button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="table-zone">
                <div class="glass-table-container">
                    <table class="cyber-table">
                        <thead>
                            <tr><th>ASSET</th><th>IDENTIFICATION</th><th>SECTOR</th><th>VALUATION</th><th>CONTROL</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $ads_query = mysqli_query($conn, "SELECT * FROM ads ORDER BY created_at DESC");
                            while($row = mysqli_fetch_assoc($ads_query)){
                                $img = (!empty($row['image_path'])) ? $row['image_path'] : 'default.png';
                                echo "
                                <tr>
                                    <td><div class='asset-img-box'><img src='uploads/{$img}'></div></td>
                                    <td><div class='asset-title'>{$row['title']}</div></td>
                                    <td><span class='sector-badge'>{$row['category_id']}</span></td>
                                    <td><span class='valuation-text'>Rs. ".number_format($row['price'])."</span></td>
                                    <td><a href='manage_ads.php?delete={$row['id']}' class='terminate-btn' onclick='return confirm(\"Terminate?\")'>TERMINATE</a></td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Form Toggle
        const openBtn = document.getElementById('openFormBtn');
        const closeBtn = document.getElementById('closeFormBtn');
        const formZone = document.getElementById('adFormContainer');

        openBtn.onclick = () => { formZone.style.display = 'block'; openBtn.style.opacity = '0'; };
        closeBtn.onclick = () => { formZone.style.display = 'none'; openBtn.style.opacity = '1'; };

        // Category Live Search
        function searchCategory(str) {
            let box = document.getElementById('suggestionBox');
            if (str.length < 1) { box.style.display = "none"; return; }
            fetch('get_categories.php?query=' + str)
                .then(res => res.text())
                .then(data => { box.innerHTML = data; box.style.display = "block"; });
        }

        function selectCategory(val) {
            document.getElementById('categorySearch').value = val;
            document.getElementById('suggestionBox').style.display = "none";
        }

        // File Label Update
        document.getElementById('fileInput').onchange = function() {
            document.getElementById('fileLabel').innerHTML = "✅ " + this.files[0].name;
        };
    </script>
</body>
</html>