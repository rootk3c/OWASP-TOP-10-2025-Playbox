<?php include 'includes/header.php'; ?>
<div class="panel">
    <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
        <h1>Page Management</h1>
        <a href="#" class="btn-primary" style="background:#888;">+ Add New</a>
    </div>
    <table class="data-table">
        <thead><tr><th>ID</th><th>Title</th><th>Slug</th><th>Status</th></tr></thead>
        <tbody>
            <tr><td>1</td><td><strong>Home</strong></td><td>/index</td><td><span class="badge badge-active">Pub</span></td></tr>
            <tr><td>2</td><td><strong>About Us</strong></td><td>/about</td><td><span class="badge badge-active">Pub</span></td></tr>
            <tr><td>3</td><td><strong>Contact</strong></td><td>/contact</td><td><span class="badge badge-inactive">Draft</span></td></tr>
        </tbody>
    </table>
</div>
<?php include 'includes/footer.php'; ?>