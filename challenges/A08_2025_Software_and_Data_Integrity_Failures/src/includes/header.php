<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BaserCMS Admin Console</title>
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main">
        <div class="top-header">
            <div class="breadcrumb">BaserCMS > System > <?= ucfirst(basename($_SERVER['PHP_SELF'], '.php')) ?></div>
            <div class="user-menu">
                <i class="fas fa-user-circle"></i> Logged in as: <strong>admin</strong>
            </div>
        </div>
        <div class="content">
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            