<?php
require_once 'config.php';

class Database {
    private $host = "localhost";
    private $db_name = "globallogistics_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            
            // mock the db conn
            $this->conn = new stdClass();
            $this->conn->status = "connected (mock)";
            
        } catch(Exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
    
    public function sanitize($input) {
        return htmlspecialchars(strip_tags($input));
    }
}
?>