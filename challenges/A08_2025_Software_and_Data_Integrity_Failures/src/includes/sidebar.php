<?php
$current = basename($_SERVER['PHP_SELF']);
function active($page) { global $current; return $current === $page ? 'active' : ''; }
?>
<div class="sidebar">
    <div class="brand">baser<span>CMS</span></div>
    
    <div class="menu-category">System</div>
    <a href="dashboard.php" class="nav-item <?= active('dashboard.php') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="config.php" class="nav-item <?= active('config.php') ?>"><i class="fas fa-cogs"></i> Site Config</a>
    
    <div class="menu-category">Content</div>
    <a href="pages.php" class="nav-item <?= active('pages.php') ?>"><i class="fas fa-file-alt"></i> Pages</a>
    <a href="posts.php" class="nav-item <?= active('posts.php') ?>"><i class="fas fa-pencil-alt"></i> Blog Posts</a>
    <a href="widgets.php" class="nav-item <?= active('widgets.php') ?>"><i class="fas fa-th-large"></i> Widget Areas</a>
    
    <div class="menu-category">Extensions</div>
    <a href="themes.php" class="nav-item <?= active('themes.php') ?>"><i class="fas fa-palette"></i> Themes</a>
    <a href="plugins.php" class="nav-item <?= active('plugins.php') ?>"><i class="fas fa-plug"></i> Plugins</a>
    <a href="market.php" class="nav-item <?= active('market.php') ?>"><i class="fas fa-shopping-cart"></i> Marketplace</a>
    
    <div class="menu-category">Tools</div>
    <a href="cache.php" class="nav-item <?= active('cache.php') ?>"><i class="fas fa-bolt"></i> Server Cache</a>
    <a href="logs.php" class="nav-item <?= active('logs.php') ?>"><i class="fas fa-bug"></i> Error Logs</a>
</div>