<?php
require_once 'config/database.php';

class Room {
    private $conn;
    private $table_name = "rooms";

    public $id;
    public $room_name;
    public $room_code;
    public $password;
    public $created_by;
    public $max_players;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($db = null) {
        $this->conn = $db ?: Database::getInstance()->getConnection();
    }

    // Tạo phòng mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (room_name, room_code, password, created_by, max_players, is_active) 
                  VALUES (:room_name, :room_code, :password, :created_by, :max_players, :is_active)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':room_name', $this->room_name);
        $stmt->bindParam(':room_code', $this->room_code);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':created_by', $this->created_by);
        $stmt->bindParam(':max_players', $this->max_players);
        $stmt->bindParam(':is_active', $this->is_active);
        
        return $stmt->execute();
    }

    // Tham gia phòng
    public function joinRoom($room_code, $password) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE room_code = :room_code AND password = :password AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_code', $room_code);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Lấy danh sách phòng
    public function getRooms($limit = null) {
        $query = "SELECT r.*, u.display_name as creator_name
                  FROM " . $this->table_name . " r
                  LEFT JOIN users u ON r.created_by = u.id
                  WHERE r.is_active = 1
                  ORDER BY r.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Lấy thông tin phòng theo ID
    public function getRoomById($id) {
        $query = "SELECT r.*, u.display_name as creator_name
                  FROM " . $this->table_name . " r
                  LEFT JOIN users u ON r.created_by = u.id
                  WHERE r.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Kiểm tra mã phòng đã tồn tại
    public function checkRoomCodeExists($room_code) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE room_code = :room_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_code', $room_code);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Tạo mã phòng ngẫu nhiên
    public function generateRoomCode() {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 6));
        } while ($this->checkRoomCodeExists($code));
        
        return $code;
    }

    // Cập nhật trạng thái phòng
    public function updateStatus($id, $is_active) {
        $query = "UPDATE " . $this->table_name . " SET is_active = :is_active, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Xóa phòng
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Lấy danh sách người chơi trong phòng
    public function getPlayersByRoom($room_id) {
        $query = "SELECT p.*, u.display_name as creator_name 
                  FROM players p 
                  JOIN users u ON p.created_by = u.id 
                  JOIN matches m ON p.match_id = m.id 
                  WHERE m.room_id = :room_id 
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Lấy ID vừa insert
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>
