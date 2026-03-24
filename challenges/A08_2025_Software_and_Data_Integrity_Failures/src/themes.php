<?php include 'includes/header.php'; ?>

<div class="panel">
    <h1>Theme Management</h1>
    
    <div style="background:#fff3cd; border:1px solid #ffeeba; color:#856404; padding:15px; margin-bottom:20px; border-radius:4px;">
        <strong><i class="fas fa-info-circle"></i> Maintenance Note:</strong> 
        Legacy theme uploader enabled. Use standard .zip packages.
    </div>

    <p style="color:#666; margin-bottom:20px;">
        Upload a theme package to install it into the system. Ensure your theme complies with BaserCMS 4.1 structure.
    </p>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div style="background:#f9f9f9; padding:30px; border:2px dashed #ccc; margin-bottom:20px; text-align:center;">
            <i class="fas fa-cloud-upload-alt" style="font-size:40px; color:#ccc; margin-bottom:10px;"></i>
            <br>
            <input type="file" name="file" required style="margin-left:80px;">
            <div class="note">Supported: .zip, .png (preview), .jpg (preview)</div>
        </div>

        <button type="submit" class="btn-primary">Install Theme</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>