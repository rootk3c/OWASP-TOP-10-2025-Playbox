<?php
// SQLite Database File
$db_file = '/var/www/html/securetransfer.db';

try {
    $conn = new PDO("sqlite:" . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("System Error: " . $e->getMessage());
}
?>