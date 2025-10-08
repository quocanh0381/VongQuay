<?php
/**
 * Database Configuration
 * Kết nối với MySQL trên InfinityFree Hosting
 * Tối ưu hóa hiệu suất và bảo mật
 */

class Database {
    private static $instance = null;
    private $host = 'sql103.infinityfree.com';
    private $db_name = 'if0_40116627_vongquay_db';
    private $username = 'if0_40116627';
    private $password = '4p9CbuWvt08';
    private $port = '3306';
    private $conn;

    // Singleton pattern để tránh tạo nhiều connection
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->getConnection();
    }

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                    PDO::ATTR_PERSISTENT => false, // Tắt persistent connection cho hosting
                    PDO::ATTR_TIMEOUT => 30
                ];
                
                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            } catch(PDOException $exception) {
                error_log("Database connection error: " . $exception->getMessage());
                throw new Exception("Không thể kết nối database. Vui lòng thử lại sau.");
            }
        }
        return $this->conn;
    }

    // Ngăn chặn clone
    private function __clone() {}
    
    // Ngăn chặn unserialize
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>
