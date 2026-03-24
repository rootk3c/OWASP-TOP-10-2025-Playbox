<?php include 'includes/header.php'; ?>
<div class="panel">
    <h1>Plugin Management</h1>
    <table class="data-table">
        <thead><tr><th>Plugin</th><th>Ver</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            <tr><td><strong>BaserCore</strong></td><td>4.1.2</td><td>Active</td><td>Locked</td></tr>
            <tr><td><strong>BcBlog</strong></td><td>1.0.5</td><td>Active</td><td><a href="#" style="color:red;">Disable</a></td></tr>
            <tr><td><strong>BcUploader</strong></td><td>2.0.1</td><td>Inactive</td><td><a href="?error=Missing Dependency: php-gd-extended">Activate</a></td></tr>
        </tbody>
    </table>
</div>
<?php include 'includes/footer.php'; ?>