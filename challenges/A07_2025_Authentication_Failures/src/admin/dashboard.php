<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'ADMIN') {
    die("Access Denied: You must be a dispatcher to view this page.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dispatcher Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container wide">
        <header>
            <h1>Dispatcher Control Panel</h1>
            <a href="../logout.php" class="btn">Logout</a>
        </header>

        <div class="grid">
            <div class="card warning">
                <h3>⚠️ System Configuration Dump</h3>
                <p><strong>Debug Mode:</strong> ENABLED</p>
                <p><strong>Environment:</strong> PRODUCTION</p>
                <p><strong>Master API Token (Flag):</strong></p>
                <code style="background: #333; color: #0f0; padding: 5px; display: block; margin-top: 5px;">
                    OWASP{m461c_h45h35_f0r_7h3_w1n}
                </code>
            </div>
            
            <div class="card">
                <h3>Active Fleet</h3>
                <table>
                    <tr><th>Truck ID</th><th>Driver</th><th>Status</th></tr>
                    <tr><td>TRK-001</td><td>John D.</td><td>Active</td></tr>
                    <tr><td>TRK-009</td><td>Sarah C.</td><td>Maintenance</td></tr>
                    <tr><td>TRK-014</td><td>Mike R.</td><td>Docking</td></tr>
                </table>
            </div>

            <div class="card">
                <h3>Recent System Logs</h3>
                <ul style="list-style: none; padding: 0; font-size: 0.9em; color: #666;">
                    <li>[<?php echo date('H:i:s'); ?>] User 'admin' session restored.</li>
                    <li>[<?php echo date('H:i:s', strtotime('-5 minutes')); ?>] Cron job 'payroll_sync' failed.</li>
                    <li>[<?php echo date('H:i:s', strtotime('-22 minutes')); ?>] Database backup completed.</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
