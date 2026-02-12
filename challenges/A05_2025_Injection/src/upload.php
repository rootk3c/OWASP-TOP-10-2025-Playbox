<?php
session_start();
if (!isset($_SESSION['user'])) { die("Access Denied"); }

if (isset($_FILES['file'])) {
    // Randomize name to force SQLi usage
    $target = "uploads/" . uniqid() . "_" . basename($_FILES['file']['name']);
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        echo "<link rel='stylesheet' href='style.css'><body class='container'>";
        echo "<h2>Upload Successful</h2>";
        echo "<p>File archived securely.</p>";
        echo "<a href='dashboard.php'>Back</a>";
    } else {
        echo "Upload failed.";
    }
}
?>