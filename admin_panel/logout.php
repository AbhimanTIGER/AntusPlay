<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Terminating Session...</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body style="display:flex; justify-content:center; align-items:center;">
    <div style="text-align:center;">
        <h2 style="color:var(--accent-color);">TERMINATING SESSION...</h2>
        <p style="color:var(--text-muted);">Securely logging you out of AntusPlay Infrastructure.</p>
        <script>setTimeout(() => { window.location.href = "login.php"; }, 1500);</script>
    </div>
</body>
</html>