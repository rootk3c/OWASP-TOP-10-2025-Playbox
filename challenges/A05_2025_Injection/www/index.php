<?php
require_once('config.php');

$error_msg = "";

// 1. Error Handling for Database Initialization
try {
    $setup = "CREATE TABLE IF NOT EXISTS active_sessions (
                session_id TEXT, 
                user_id INTEGER, 
                created_at TEXT
              );
              INSERT INTO active_sessions (session_id, user_id, created_at) 
              SELECT 'admin_session_x99', 1, '2023-05-20' 
              WHERE NOT EXISTS (SELECT 1 FROM active_sessions);";

    $conn->exec($setup);
} catch (PDOException $e) {
    // If the DB is read-only or busy, we catch it here
    $error_msg = "System Error: Database is currently locked or read-only.";
}

// 2. Logic for Wrong Credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    // Simple hardcoded check for the 'Login' UI
    if ($user === 'admin' && $pass === 'password123') {
        echo "<script>alert('Login Successful! (This is just a demo login)');</script>";
    } else {
        $error_msg = "Invalid Username or Access Code.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SecureTransfer Lite</title>
    <style>
        body { font-family: monospace; background: #222; color: #0f0; text-align: center; padding-top: 100px; }
        .box { border: 1px solid #0f0; width: 400px; margin: 0 auto; padding: 20px; }
        input { background: #000; border: 1px solid #0f0; color: #fff; padding: 10px; width: 90%; margin: 10px 0; }
        button { background: #0f0; color: #000; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; }
        .error { color: #ff5555; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <h2>SECURE TRANSFER GATEWAY</h2>
        
        <?php if ($error_msg): ?>
            <div class="error"><?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>

        <p>System Online. Memory Optimized.</p>
        <form action="" method="POST">
            <input type="text" name="user" placeholder="USER_ID" required>
            <input type="password" name="pass" placeholder="ACCESS_CODE" required>
            <button type="submit">AUTHENTICATE</button>
        </form>
    </div>
</body>
</html>