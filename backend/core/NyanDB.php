<?php

class NyanDB 
{
    private const HOST = 'localhost';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const DBNAME = 'nyan_catering';
    public $db;

    public function __construct() {
        $this->connect();//connect is a private function
    }

    private function connect() {
        $this->db = new mysqli(self::HOST, self::USERNAME, self::PASSWORD, self::DBNAME);
        
        // Check for connection errors
        if ($this->db->connect_errno) {
            throw new Exception("Error: Could not connect to database. Error code: " . $this->db->connect_error, 69001);
        }
    }

    public function close() {
        if ($this->db) {
            $this->db->close();
            $this->db = null;
        }
    }

    public function __destruct() {
        $this->close();
    }

    private function prepare($sql,$params = []){
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare Error: " . $this->db->error, 69002);
        }
        if (!empty($params)){
            $types = str_repeat('s',count($params));
            $stmt->bind_param($types, ...$params);
        }
        return $stmt;
    }

    public function query($sql,$params){
        $stmt = $this->prepare($sql,$params);
        $result = $stmt->execute();
        if (!$result) {throw new Exception("Query Error: " . $stmt->error, 69001);}
        if (stripos(trim($sql), 'SELECT') === 0) {
            return $stmt->get_result(); // Return the result set for SELECT queries
        }
        if (stripos(trim($sql), 'INSERT') === 0) {
            return $this->db->insert_id; // Return the result set for INSERT queries
        }
        return $result;
    }

    public static function single_query($sql,$params) {
        $database = new NyanDB();
        $result = $database->query($sql,$params);
        $database->close();
        return $result;
    }
}
?>