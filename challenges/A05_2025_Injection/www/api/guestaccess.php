<?php
require_once('../config.php');
require_once('../classes/Session.php');

header('Content-Type: application/json');
$input = $_POST['transaction_id'] ?? '';

$sessionMgr = new SessionManager($conn);

if ($sessionMgr->validate_session($input)) {
    // Returns success only if a real session was updated
    echo json_encode(["status" => "success", "message" => "Session Active"]);
} else {
    // Returns error for fake IDs, SQL syntax errors, OR successful RCE payloads
    // (Since ATTACH doesn't "update" a row in the active_sessions table)
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Invalid Session"]);
}