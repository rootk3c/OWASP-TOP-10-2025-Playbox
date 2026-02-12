<?php
// CRITICAL FIX: PHP 8.2 throws Fatal Errors on connection failure by default.
// We disable this to prevent the HTTP 500 crash.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli("localhost", "app_user", "pass123", "archive_db");

if ($conn->connect_error) {
    // If DB is down, show a clear message instead of a blank 500 error
    http_response_code(503); 
    die("<h1>Service Unavailable</h1><p>Database connection failed. The container might still be booting.</p>");
}
?>