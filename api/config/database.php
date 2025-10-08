<?php
/**
 * Database Configuration
 * Cấu hình kết nối cơ sở dữ liệu vongquay_db
 */

class DatabaseConfig {
    // Cấu hình database
    private static $config = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'username' => 'root',
        'password' => '',
        'database' => 'vongquay_db',
        'charset' => 'utf8mb4',
        'timezone' => '+00:00'
    ];

    /**
     * Lấy cấu hình database
     */
    public static function getConfig() {
        return self::$config;
    }

    /**
     * Lấy DSN string
     */
    public static function getDSN() {
        $config = self::$config;
        return "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    }
}
?>