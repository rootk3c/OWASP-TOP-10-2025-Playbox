<?php
class SessionManager {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function validate_session($sess_token) {
        // REAL-WORLD SCENARIO: The developer wants to update the 'last_active' 
        // timestamp every time a session is checked. 
        // They use exec() because they aren't fetching rows, just updating.
        $sql = "UPDATE active_sessions SET last_seen = datetime('now') WHERE session_id = '" . $sess_token . "'";
        
        try {
            // exec() is the "Holy Grail" for SQLite RCE because it 
            // natively supports stacked queries (semicolons).
            $affected = $this->db->exec($sql);

            // If the UPDATE modified at least 1 row, it's a valid session.
            // If it modified 0 rows (like when you run an ATTACH command), 
            // it returns 0, which PHP treats as false.
            return ($affected > 0);
            
        } catch (Exception $e) {
            // Syntax errors (like unclosed quotes) land here.
            return false;
        }
    }
}