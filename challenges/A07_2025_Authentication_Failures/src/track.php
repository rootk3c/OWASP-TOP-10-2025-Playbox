<?php 
require_once 'includes/utils.php'; 
require_once 'includes/config.php';

$result = null;
if (isset($_GET['tracking_id'])) {
    $id = $_GET['tracking_id'];
    // Mock Database for tracking
    $db = [
        '1001' => ['status' => 'DELIVERED', 'loc' => 'Warehouse A'],
        '1002' => ['status' => 'TRANSIT', 'loc' => 'Route 66'],
        '1003' => ['status' => 'PENDING', 'loc' => 'Docking Bay'],
    ];
    
    if (array_key_exists($id, $db)) {
        $result = $db[$id];
    } else {
        $error = "Tracking ID not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Package - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a> | <a href="track.php">Track Package</a> | <a href="login.php">Login</a>
    </nav>
    <div class="container">
        <h2>📦 Package Tracker</h2>
        <form method="GET">
            <input type="text" name="tracking_id" placeholder="Ex: 1001">
            <button type="submit">Track</button>
        </form>

        <?php if($result): ?>
            <div class="card">
                <h3>Status: <?php echo format_status($result['status']); ?></h3>
                <p>Current Location: <?php echo $result['loc']; ?></p>
            </div>
        <?php elseif(isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>


