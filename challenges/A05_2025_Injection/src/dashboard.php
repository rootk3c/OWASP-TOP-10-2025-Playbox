<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Archive Dashboard</h2>
        <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></p>
        
        <div class="card">
            <h3>Upload Archive</h3>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" required>
                <button type="submit">Upload</button>
            </form>
        </div>

        <div class="card">
            <h3>Search Archives</h3>
            <p>Find existing folders by ID.</p>
            <form action="search.php" method="GET">
                <input type="text" name="id" placeholder="Folder ID (e.g., 1)" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</body>
</html>