<?php include 'includes/header.php'; ?>
<div class="panel" style="text-align:center; padding:50px;">
    <h1>BaserCMS Marketplace</h1>
    <p>Connecting to repo.basercms.net...</p>
    <div class="loading-spinner"></div>
    <script>
        setTimeout(() => {
            document.querySelector('.loading-spinner').style.display = 'none';
            document.querySelector('p').innerHTML = '<span style="color:red">Connection Timeout (Error 504)</span>';
        }, 1500);
    </script>
</div>
<?php include 'includes/footer.php'; ?>