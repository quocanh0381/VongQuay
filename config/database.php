<?php
/**
 * Database Configuration for InfinityFree Hosting
 * (Hữu Trí - bản chuẩn hoạt động trực tiếp trên hosting)
 */

class Database {
    private static $instance = null;
    private $conn;

    // Thông tin kết nối chính xác
    private $host = 'sql109.infinityfree.com';
    private $db_name = 'if0_40117568_vongquay_db';
    private $username = 'if0_40117568';
    private $password = '5i60Js2mT7WYj3';
    private $port = '3306';

    // Singleton pattern
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_TIMEOUT => 10
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $e) {
            // In ra lỗi dễ hiểu nếu kết nối thất bại
            echo "<div style='background:#111;color:#f55;padding:15px;border-radius:8px;font-size:15px'>
            ❌ <b>Lỗi kết nối CSDL:</b><br>
            <b>SQLSTATE:</b> " . htmlspecialchars($e->getCode()) . "<br>
            <b>Chi tiết:</b> " . htmlspecialchars($e->getMessage()) . "<br><br>
            👉 Kiểm tra lại:<br>
            - Host: {$this->host}<br>
            - Database: {$this->db_name}<br>
            - Username: {$this->username}<br>
            - Password: (ẩn để bảo mật)<br>
            </div>";
            exit;
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>
