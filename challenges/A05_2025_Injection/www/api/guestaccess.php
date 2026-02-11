<?php
require_once('../config.php');
require_once('../classes/Session.php');

header('Content-Type: application/json');

// Simulate headers used in enterprise apps
$headers = getallheaders();
$session_token = "";

// Look for token in Header or POST body
if (isset($headers['X-SiLock-Session'])) {
    $session_token = $headers['X-SiLock-Session'];
} elseif (isset($_POST['transaction_id'])) {
    $session_token = $_POST['transaction_id'];
} else {
    echo json_encode(["status" => "error", "message" => "Missing Transaction ID"]);
    exit;
}

$sessionMgr = new SessionManager($conn);
$isValid = $sessionMgr->validate_session($session_token);

if ($isValid) {
    echo json_encode(["status" => "success", "action" => "download_manifest"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Session"]);
}
?>

