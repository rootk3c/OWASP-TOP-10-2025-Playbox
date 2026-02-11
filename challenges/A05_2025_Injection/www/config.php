<?php
try {
    $db_path = '/var/www/html/securetransfer.db';
    $conn = new PDO("sqlite:$db_path");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("CREATE TABLE IF NOT EXISTS active_sessions (
        session_id TEXT PRIMARY KEY, 
        last_seen DATETIME
    )");

    // Seed a valid session so the student can test 'OR '1'='1
    $conn->exec("INSERT OR IGNORE INTO active_sessions (session_id) VALUES ('valid_admin_session')");
} catch(PDOException $e) {
    die("DB Error");
}

