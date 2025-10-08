<?php
/**
 * Player Controller
 * Xử lý các API cho Player
 */

class PlayerController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Lấy danh sách players
     */
    public function getPlayers($params = []) {
        try {
            $sql = "SELECT p.*, m.match_name, u.display_name as created_by_name 
                    FROM players p 
                    LEFT JOIN matches m ON p.match_id = m.id 
                    LEFT JOIN users u ON p.created_by = u.id";
            $whereClause = [];
            $params_array = [];

            // Filter by match_id
            if (isset($params['match_id'])) {
                $whereClause[] = "p.match_id = :match_id";
                $params_array['match_id'] = $params['match_id'];
            }

            // Filter by created_by
            if (isset($params['created_by'])) {
                $whereClause[] = "p.created_by = :created_by";
                $params_array['created_by'] = $params['created_by'];
            }

            if (!empty($whereClause)) {
                $sql .= " WHERE " . implode(' AND ', $whereClause);
            }

            // Order by
            $orderBy = $params['order_by'] ?? 'totalScore';
            $orderDir = $params['order_dir'] ?? 'DESC';
            $sql .= " ORDER BY p.{$orderBy} {$orderDir}";

            // Pagination
            $limit = $params['limit'] ?? 50;
            $offset = $params['offset'] ?? 0;
            $sql .= " LIMIT {$limit} OFFSET {$offset}";

            $players = $this->db->fetchAll($sql, $params_array);

            // Format response
            $formattedPlayers = array_map(function($player) {
                return [
                    'id' => (int)$player['id'],
                    'match_id' => (int)$player['match_id'],
                    'match_name' => $player['match_name'],
                    'name' => $player['name'],
                    'personalSkill' => (int)$player['personalSkill'],
                    'mapReading' => (int)$player['mapReading'],
                    'teamwork' => (int)$player['teamwork'],
                    'reaction' => (int)$player['reaction'],
                    'experience' => (int)$player['experience'],
                    'position' => (int)$player['position'],
                    'totalScore' => (int)$player['totalScore'],
                    'created_by' => (int)$player['created_by'],
                    'created_by_name' => $player['created_by_name'],
                    'created_at' => $player['created_at']
                ];
            }, $players);

            echo json_encode([
                'success' => true,
                'data' => $formattedPlayers,
                'count' => count($formattedPlayers)
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get players',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy player theo ID
     */
    public function getPlayer($playerId) {
        try {
            $sql = "SELECT p.*, m.match_name, u.display_name as created_by_name 
                    FROM players p 
                    LEFT JOIN matches m ON p.match_id = m.id 
                    LEFT JOIN users u ON p.created_by = u.id 
                    WHERE p.id = :id";
            $player = $this->db->fetchOne($sql, ['id' => $playerId]);

            if (!$player) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Player not found'
                ]);
                return;
            }

            // Format response
            $formattedPlayer = [
                'id' => (int)$player['id'],
                'match_id' => (int)$player['match_id'],
                'match_name' => $player['match_name'],
                'name' => $player['name'],
                'personalSkill' => (int)$player['personalSkill'],
                'mapReading' => (int)$player['mapReading'],
                'teamwork' => (int)$player['teamwork'],
                'reaction' => (int)$player['reaction'],
                'experience' => (int)$player['experience'],
                'position' => (int)$player['position'],
                'totalScore' => (int)$player['totalScore'],
                'created_by' => (int)$player['created_by'],
                'created_by_name' => $player['created_by_name'],
                'created_at' => $player['created_at']
            ];

            echo json_encode([
                'success' => true,
                'data' => $formattedPlayer
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get player',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tạo player mới
     */
    public function createPlayer($data) {
        try {
            // Validation
            $errors = $this->validatePlayerData($data);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                return;
            }

            // Check if match exists
            $match = $this->db->fetchOne(
                "SELECT id FROM matches WHERE id = :match_id",
                ['match_id' => $data['match_id']]
            );

            if (!$match) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Match not found'
                ]);
                return;
            }

            // Calculate total score
            $totalScore = ($data['personalSkill'] ?? 0) + 
                         ($data['mapReading'] ?? 0) + 
                         ($data['teamwork'] ?? 0) + 
                         ($data['reaction'] ?? 0) + 
                         ($data['experience'] ?? 0) + 
                         ($data['position'] ?? 0);

            // Prepare data
            $playerData = [
                'match_id' => $data['match_id'],
                'name' => $data['name'],
                'personalSkill' => $data['personalSkill'] ?? 0,
                'mapReading' => $data['mapReading'] ?? 0,
                'teamwork' => $data['teamwork'] ?? 0,
                'reaction' => $data['reaction'] ?? 0,
                'experience' => $data['experience'] ?? 0,
                'position' => $data['position'] ?? 0,
                'totalScore' => $totalScore,
                'created_by' => $data['created_by']
            ];

            $playerId = $this->db->insert('players', $playerData);

            echo json_encode([
                'success' => true,
                'message' => 'Player created successfully',
                'data' => [
                    'id' => $playerId,
                    'totalScore' => $totalScore
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create player',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Cập nhật player
     */
    public function updatePlayer($playerId, $data) {
        try {
            // Check if player exists
            $existingPlayer = $this->db->fetchOne(
                "SELECT id FROM players WHERE id = :id",
                ['id' => $playerId]
            );

            if (!$existingPlayer) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Player not found'
                ]);
                return;
            }

            // Prepare update data
            $updateData = [];
            if (isset($data['name'])) $updateData['name'] = $data['name'];
            if (isset($data['personalSkill'])) $updateData['personalSkill'] = $data['personalSkill'];
            if (isset($data['mapReading'])) $updateData['mapReading'] = $data['mapReading'];
            if (isset($data['teamwork'])) $updateData['teamwork'] = $data['teamwork'];
            if (isset($data['reaction'])) $updateData['reaction'] = $data['reaction'];
            if (isset($data['experience'])) $updateData['experience'] = $data['experience'];
            if (isset($data['position'])) $updateData['position'] = $data['position'];

            if (empty($updateData)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No data to update'
                ]);
                return;
            }

            // Recalculate total score if skills changed
            if (isset($data['personalSkill']) || isset($data['mapReading']) || 
                isset($data['teamwork']) || isset($data['reaction']) || 
                isset($data['experience']) || isset($data['position'])) {
                
                $currentPlayer = $this->db->fetchOne(
                    "SELECT * FROM players WHERE id = :id",
                    ['id' => $playerId]
                );

                $totalScore = ($updateData['personalSkill'] ?? $currentPlayer['personalSkill']) + 
                             ($updateData['mapReading'] ?? $currentPlayer['mapReading']) + 
                             ($updateData['teamwork'] ?? $currentPlayer['teamwork']) + 
                             ($updateData['reaction'] ?? $currentPlayer['reaction']) + 
                             ($updateData['experience'] ?? $currentPlayer['experience']) + 
                             ($updateData['position'] ?? $currentPlayer['position']);

                $updateData['totalScore'] = $totalScore;
            }

            $rowsAffected = $this->db->update(
                'players',
                $updateData,
                'id = :id',
                ['id' => $playerId]
            );

            echo json_encode([
                'success' => true,
                'message' => 'Player updated successfully',
                'data' => ['rows_affected' => $rowsAffected]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update player',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xóa player
     */
    public function deletePlayer($playerId) {
        try {
            // Check if player exists
            $existingPlayer = $this->db->fetchOne(
                "SELECT id FROM players WHERE id = :id",
                ['id' => $playerId]
            );

            if (!$existingPlayer) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Player not found'
                ]);
                return;
            }

            $rowsAffected = $this->db->delete('players', 'id = :id', ['id' => $playerId]);

            echo json_encode([
                'success' => true,
                'message' => 'Player deleted successfully',
                'data' => ['rows_affected' => $rowsAffected]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete player',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Validation cho player data
     */
    private function validatePlayerData($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Player name is required';
        } elseif (strlen($data['name']) < 2) {
            $errors[] = 'Player name must be at least 2 characters';
        }

        if (empty($data['match_id'])) {
            $errors[] = 'Match ID is required';
        }

        if (empty($data['created_by'])) {
            $errors[] = 'Created by is required';
        }

        // Validate skill scores (1-5)
        $skills = ['personalSkill', 'mapReading', 'teamwork', 'reaction', 'experience', 'position'];
        foreach ($skills as $skill) {
            if (isset($data[$skill]) && ($data[$skill] < 1 || $data[$skill] > 5)) {
                $errors[] = ucfirst($skill) . ' must be between 1 and 5';
            }
        }

        return $errors;
    }
}
?>
