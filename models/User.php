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

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đăng nhập
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username AND password = :password";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Đăng ký
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, password, display_name) 
                  VALUES (:username, :password, :display_name)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':display_name', $this->display_name);
        
        return $stmt->execute();
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
