<?php
require_once 'config/database.php';

class Player {
    private $conn;
    private $table_name = "players";

    public $id;
    public $match_id;
    public $name;
    public $personalSkill;
    public $mapReading;
    public $teamwork;
    public $reaction;
    public $experience;
    public $position;
    public $totalScore;
    public $created_by;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm player vào match
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (match_id, name, personalSkill, mapReading, teamwork, reaction, experience, position, totalScore, created_by) 
                  VALUES (:match_id, :name, :personalSkill, :mapReading, :teamwork, :reaction, :experience, :position, :totalScore, :created_by)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':match_id', $this->match_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':personalSkill', $this->personalSkill);
        $stmt->bindParam(':mapReading', $this->mapReading);
        $stmt->bindParam(':teamwork', $this->teamwork);
        $stmt->bindParam(':reaction', $this->reaction);
        $stmt->bindParam(':experience', $this->experience);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':totalScore', $this->totalScore);
        $stmt->bindParam(':created_by', $this->created_by);
        
        return $stmt->execute();
    }

    // Lấy danh sách player theo match_id
    public function getPlayersByMatch($match_id) {
        $query = "SELECT p.*, u.display_name as creator_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.created_by = u.id
                  WHERE p.match_id = :match_id
                  ORDER BY p.totalScore DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':match_id', $match_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Lấy player theo ID
    public function getPlayerById($id) {
        $query = "SELECT p.*, u.display_name as creator_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.created_by = u.id
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Cập nhật thông tin player
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, personalSkill = :personalSkill, mapReading = :mapReading, 
                      teamwork = :teamwork, reaction = :reaction, experience = :experience, 
                      position = :position, totalScore = :totalScore
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':personalSkill', $this->personalSkill);
        $stmt->bindParam(':mapReading', $this->mapReading);
        $stmt->bindParam(':teamwork', $this->teamwork);
        $stmt->bindParam(':reaction', $this->reaction);
        $stmt->bindParam(':experience', $this->experience);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':totalScore', $this->totalScore);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // Xóa player
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // Tính tổng điểm
    public function calculateTotalScore() {
        return $this->personalSkill + $this->mapReading + $this->teamwork + 
               $this->reaction + $this->experience;
    }

    // Chia team cân bằng
    public function balanceTeams($players) {
        // Sắp xếp players theo totalScore giảm dần
        usort($players, function($a, $b) {
            return $b['totalScore'] - $a['totalScore'];
        });

        $team1 = [];
        $team2 = [];
        $team1Score = 0;
        $team2Score = 0;

        // Chia players vào 2 team
        foreach ($players as $player) {
            if ($team1Score <= $team2Score) {
                $team1[] = $player;
                $team1Score += $player['totalScore'];
            } else {
                $team2[] = $player;
                $team2Score += $player['totalScore'];
            }
        }

        return [
            'team1' => $team1,
            'team2' => $team2,
            'team1Score' => $team1Score,
            'team2Score' => $team2Score,
            'balance' => abs($team1Score - $team2Score)
        ];
    }
}
?>
