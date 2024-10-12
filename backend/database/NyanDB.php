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
            throw new Exception("Error: Could not connect to database. Error code: " . $this->db->connect_error);
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

    public static function single_query($sql) {
        $database = new NyanDB();
        $result = $database->db->query($sql);
        if (!$result) {throw new Exception("Query Error: " . $database->db->error);}
        $database->close();
        return $result;
    }

    public function query($sql){
        $result = this->db->query($sql);
        if (!$result) {throw new Exception("Query Error: " . $database->db->error);}
        return $result;
    }
}
?>