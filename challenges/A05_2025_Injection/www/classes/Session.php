<?php
class SessionManager {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function validate_session($sess_token) {
        // VULNERABLE: Direct concatenation
        $sql = "SELECT * FROM active_sessions WHERE session_id = '" . $sess_token . "'";
        
        try {
            // Execute the query
            $stmt = $this->db->query($sql);
            if ($stmt) {
                $result = $stmt->fetch();
                if ($result) {
                    return true;
                }
            }
        } catch (Exception $e) {
            // In a real CTF, you might hide this. 
            // For learning, seeing the error helps debug the payload.
            return false;
        }
        return false;
    }
}
?>


