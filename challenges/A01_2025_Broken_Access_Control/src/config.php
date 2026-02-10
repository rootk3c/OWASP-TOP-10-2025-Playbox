<?php include 'includes/header.php'; ?>
<div class="panel">
    <h1>Site Configuration</h1>
    <form>
        <div class="form-group"><label class="form-label">Site Title</label><input type="text" class="form-input" value="Intranet Portal"></div>
        <div class="form-group"><label class="form-label">Admin Email</label><input type="email" class="form-input" value="admin@corp.local"></div>
        <div class="form-group"><label class="form-label">Debug Mode</label><select class="form-select"><option>0 (Off)</option><option selected>1 (On)</option></select></div>
        <br>
        <a href="?msg=Settings saved." class="btn-primary">Save Changes</a>
    </form>
</div>
<?php include 'includes/footer.php'; ?>