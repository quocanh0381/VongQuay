<?php
require_once 'config/database.php';

class GameMatch {
    private $conn;
    private $table_name = "matches";

    public $id;
    public $match_name;
    public $room_id;
    public $created_by;
    public $created_at;

    public function __construct($db = null) {
        $this->conn = $db ?: Database::getInstance()->getConnection();
    }

    // Tạo match mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (match_name, room_id, created_by) 
                  VALUES (:match_name, :room_id, :created_by)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':match_name', $this->match_name);
        $stmt->bindParam(':room_id', $this->room_id);
        $stmt->bindParam(':created_by', $this->created_by);
        
        return $stmt->execute();
    }

    // Lấy danh sách match
    public function getMatches() {
        $query = "SELECT m.*, u.display_name as creator_name
                  FROM " . $this->table_name . " m
                  LEFT JOIN users u ON m.created_by = u.id
                  ORDER BY m.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Lấy match theo ID
    public function getMatchById($id) {
        $query = "SELECT m.*, u.display_name as creator_name
                  FROM " . $this->table_name . " m
                  LEFT JOIN users u ON m.created_by = u.id
                  WHERE m.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Lấy match theo room_id
    public function getMatchesByRoom($room_id) {
        $query = "SELECT m.*, u.display_name as creator_name
                  FROM " . $this->table_name . " m
                  LEFT JOIN users u ON m.created_by = u.id
                  WHERE m.room_id = :room_id
                  ORDER BY m.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Xóa match
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Lấy ID vừa insert
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>
