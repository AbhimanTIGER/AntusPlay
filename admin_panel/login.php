<?php
session_start();
include 'db.php';

$error = "";

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    // ඔයාගේ Database එකේ 'admins' table එකෙන් check කිරීම
    $query = "SELECT * FROM admins WHERE admin_username='$user' AND admin_password='$pass'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin_user'] = $user;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "INVALID ACCESS KEY";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Authorization | AntusPlay PRO</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;500;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #00ff88;
            --secondary: #00e1ff;
            --bg: #020205;
            --glass: rgba(255, 255, 255, 0.03);
            --glow: rgba(0, 255, 136, 0.3);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            perspective: 1000px;
        }

        /* --- ALWAYS RUNNING ANIMATED BACKGROUND --- */
        .bg-animate {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 50%, #0a1a12 0%, #020205 100%);
            z-index: -1;
        }

        .orb {
            position: absolute;
            width: 400px; height: 400px;
            background: var(--primary);
            filter: blur(150px);
            border-radius: 50%;
            opacity: 0.15;
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-20%, -20%); }
            to { transform: translate(20%, 20%); }
        }

        /* --- LOGIN CARD DESIGN --- */
        .login-card {
            background: var(--glass);
            backdrop-filter: blur(30px);
            padding: 50px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 420px;
            text-align: center;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(255,255,255,0.02);
            animation: cardEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntry {
            from { opacity: 0; transform: scale(0.9) translateY(50px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* --- LOGO AREA --- */
        .logo-container {
            position: relative;
            margin-bottom: 30px;
            display: inline-block;
        }

        .company-logo {
            width: 85px; height: 85px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 32px; font-weight: 900; color: #000;
            box-shadow: 0 0 30px var(--glow);
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 20px var(--glow); }
            50% { box-shadow: 0 0 45px var(--glow); }
            100% { box-shadow: 0 0 20px var(--glow); }
        }

        h2 { 
            font-family: 'Orbitron', sans-serif; 
            color: white; letter-spacing: 4px; 
            font-size: 20px; margin-bottom: 40px;
            text-transform: uppercase;
        }

        /* --- PROFESSIONAL INPUT BOXES --- */
        .input-box {
            position: relative;
            margin-bottom: 30px;
        }

        .input-pro {
            width: 100%;
            padding: 18px 20px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: white;
            font-size: 15px;
            outline: none;
            transition: all 0.4s ease;
            box-sizing: border-box;
        }

        .input-pro:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: var(--primary);
            box-shadow: 0 0 25px rgba(0, 255, 136, 0.15);
            transform: translateY(-2px);
        }

        .input-box label {
            position: absolute;
            top: -10px; left: 15px;
            background: #0d1a14;
            padding: 0 8px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            border-radius: 4px;
        }

        /* --- HOVER EFFECT BUTTON --- */
        .btn-pro {
            width: 100%;
            padding: 20px;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 16px;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 10px;
        }

        .btn-pro:hover {
            transform: scale(1.03) translateY(-3px);
            box-shadow: 0 15px 35px var(--glow);
            letter-spacing: 4px;
        }

        .btn-pro:active { transform: scale(0.98); }

        /* Error Effect */
        .error-msg {
            color: #ff4d4d;
            background: rgba(255, 77, 77, 0.1);
            padding: 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 77, 77, 0.2);
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>

    <div class="bg-animate">
        <div class="orb"></div>
    </div>

    <div class="login-card">
        <div class="logo-container">
            <div class="company-logo">AP</div>
        </div>

        <h2>Admin Access</h2>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-box">
                <label>IDENTITY CODE</label>
                <input type="text" name="username" class="input-pro" placeholder="Enter Admin ID" required>
            </div>

            <div class="input-box">
                <label>SECURITY PASSKEY</label>
                <input type="password" name="password" class="input-pro" placeholder="••••••••" required>
            </div>

            <button type="submit" name="login" class="btn-pro">Authorize Session</button>
        </form>
    </div>

</body>
</html>