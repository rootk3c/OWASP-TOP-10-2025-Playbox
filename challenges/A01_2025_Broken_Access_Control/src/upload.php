<?php
// BaserCMS 4.1.2 Vulnerable Upload Logic
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    // 1. ISOLATION: Random folder so players don't see each other's hacks
    $random_id = bin2hex(random_bytes(16));
    $base_dir = 'assets/themes/';
    $user_folder = $base_dir . $random_id . '/';

    // Create directory in RAM (tmpfs)
    if (!is_dir($user_folder)) {
        mkdir($user_folder, 0777, true);
    }

    $target_file = $user_folder . basename($file['name']);
    
    // 2. THE VULNERABILITY: Weak MIME Type Check (CVE-2020-15277 style)
    $allowed_mimes = [
        'image/jpeg', 
        'image/png', 
        'image/gif', 
        'application/zip', 
        'application/x-zip-compressed'
    ];
    
    // If the attacker changes Content-Type in Burp to 'application/zip', this passes.
    if (in_array($file['type'], $allowed_mimes)) {
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // SUCCESS: Redirect back to themes.php with the link
            $msg = "Theme package uploaded! Staged at: <a href='$target_file' target='_blank'>$target_file</a>";
            // We use urlencode to pass the HTML safely in the query string
            header("Location: themes.php?msg=" . urlencode($msg));
            exit;
        } else {
            header("Location: themes.php?error=Write Permission Failed on /app/webroot/theme/");
            exit;
        }
    } else {
        // FAIL
        $err = "Security Violation: The file type " . $file['type'] . " is not permitted.";
        header("Location: themes.php?error=" . urlencode($err));
        exit;
    }
} else {
    header("Location: themes.php");
    exit;
}
?>

