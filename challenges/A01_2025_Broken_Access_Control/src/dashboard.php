<?php include 'includes/header.php'; ?>

<h1>System Dashboard</h1>
<div class="dashboard-grid">
    <div class="stat-card"><div class="stat-number">12,405</div><div class="stat-label">Page Views</div></div>
    <div class="stat-card"><div class="stat-number">45</div><div class="stat-label">Posts</div></div>
    <div class="stat-card"><div class="stat-number" style="color:#d9534f">0</div><div class="stat-label">Alerts</div></div>
    <div class="stat-card"><div class="stat-number">v4.1.2</div><div class="stat-label">Version</div></div>
</div>
<div class="panel">
    <h2>Recent Activity</h2>
    <table class="data-table">
        <thead><tr><th>Time</th><th>User</th><th>Action</th></tr></thead>
        <tbody>
            <tr><td>2 mins ago</td><td>admin</td><td>Logged in</td></tr>
            <tr><td>1 hour ago</td><td>system</td><td>Cache cleared</td></tr>
            <tr><td>Yesterday</td><td>editor</td><td>Updated "About"</td></tr>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>