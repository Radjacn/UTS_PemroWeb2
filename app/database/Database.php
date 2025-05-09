<?php

namespace App\Database;

use PDO;

class Database
{
    private $host = 'localhost';
    private $db_name = 'kelolamhs_radjangili';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect()
    {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                echo "Connection error: " . $e->getMessage();
            }
        }
        return $this->conn;
    }
}
