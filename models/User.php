<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $display_name;
    public $created_at;

    public function __construct($db = null) {
        $this->conn = $db ?: Database::getInstance()->getConnection();
    }

    // Đăng nhập
    public function login($username, $password) {
        $query = "SELECT id, username, display_name, created_at FROM " . $this->table_name . " WHERE username = :username AND password = :password LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Đăng ký
    public function register() {
        // Kiểm tra username đã tồn tại chưa
        if ($this->usernameExists($this->username)) {
            return false;
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, password, display_name) 
                  VALUES (:username, :password, :display_name)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':display_name', $this->display_name, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    // Kiểm tra username đã tồn tại
    private function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch() !== false;
    }

    // Lấy thông tin user theo ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Cập nhật thông tin user
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET display_name = :display_name 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':display_name', $this->display_name);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // Kiểm tra username đã tồn tại
    public function checkUsernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Lấy ID vừa insert
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>
