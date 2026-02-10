<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/utils.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if ($username === 'driver') {
        if ($password === 'drive123') {
            $error = "Driver portal is currently under maintenance. Please contact dispatch.";
        } else {
            $error = "Invalid driver credentials.";
        }
    }
    
    elseif ($username === 'admin') {
        $input_hash = md5($password);

        if ($input_hash == ADMIN_RECOVERY_HASH) {
            $_SESSION['user_role'] = 'ADMIN';
            $_SESSION['user'] = $username;
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $error = "Invalid Dispatcher Credentials.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav><a href="index.php">← Back to Home</a></nav>
    <div class="login-box">
        <h2>Driver & Dispatch Login</h2>
        <?php if($error) echo "<div class='alert'>$error</div>"; ?>
        
        <form method="POST">
            <label>Employee ID / Username</label>
            <input type="text" name="username" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>

