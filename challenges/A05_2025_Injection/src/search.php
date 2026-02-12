<?php
include 'db.php';
session_start();

if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit; }

$id = $_GET['id'];

// VULNERABILITY: Direct Concatenation
// Allows: UNION SELECT ... INTO OUTFILE
$sql = "SELECT folder_name, owner, file_path FROM folders WHERE id = " . $id;

echo "<link rel='stylesheet' href='style.css'><body class='container'>";
echo "<h2>Search Results</h2>";

try {
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='result'>";
            echo "<strong>Folder:</strong> " . $row['folder_name'] . "<br>";
            echo "<strong>Owner:</strong> " . $row['owner'] . "<br>";
            echo "<strong>Path:</strong> " . $row['file_path'];
            echo "</div><hr>";
        }
    } else {
        echo "<p>No folder found.</p>";
    }
} catch (Exception $e) {
    // Blind error
    echo "<p>Error searching archive.</p>";
}

echo "<a href='dashboard.php'>Back to Dashboard</a>";
?>