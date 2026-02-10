<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a> | 
        <a href="track.php">Track Package</a> | 
        <a href="contact.php">Support</a> | 
        <a href="login.php">Driver Login</a>
    </nav>

    <div class="container">
        <h1>Welcome to the Driver Portal</h1>
        <p>System Status: Online (v<?php echo VERSION; ?>)</p>
        
        <div class="news-feed">
            <h3>📢 Latest Announcements</h3>
            <ul>
                <li><strong><?php echo date('M d'); ?>:</strong> System maintenance scheduled for Sunday 2 AM.</li>
                <li><strong><?php echo date('M d', strtotime('-2 days')); ?>:</strong> New fuel reimbursement policy in effect.</li>
                <li><strong><?php echo date('M d', strtotime('-5 days')); ?>:</strong> Welcome to our new dispatchers in the Midwest region.</li>
            </ul>
        </div>
    </div>
</body>
</html>


