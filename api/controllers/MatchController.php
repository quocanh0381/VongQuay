<?php
/**
 * Match Controller
 * Xử lý các API cho Match
 */

class MatchController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Lấy danh sách matches
     */
    public function getMatches($params = []) {
        try {
            $sql = "SELECT m.*, r.room_name, u.display_name as created_by_name 
                    FROM matches m 
                    LEFT JOIN rooms r ON m.room_id = r.id 
                    LEFT JOIN users u ON m.created_by = u.id";
            $whereClause = [];
            $params_array = [];

            // Filter by room_id
            if (isset($params['room_id'])) {
                $whereClause[] = "m.room_id = :room_id";
                $params_array['room_id'] = $params['room_id'];
            }

            // Filter by created_by
            if (isset($params['created_by'])) {
                $whereClause[] = "m.created_by = :created_by";
                $params_array['created_by'] = $params['created_by'];
            }

            if (!empty($whereClause)) {
                $sql .= " WHERE " . implode(' AND ', $whereClause);
            }

            // Order by
            $orderBy = $params['order_by'] ?? 'created_at';
            $orderDir = $params['order_dir'] ?? 'DESC';
            $sql .= " ORDER BY m.{$orderBy} {$orderDir}";

            // Pagination
            $limit = $params['limit'] ?? 50;
            $offset = $params['offset'] ?? 0;
            $sql .= " LIMIT {$limit} OFFSET {$offset}";

            $matches = $this->db->fetchAll($sql, $params_array);

            // Format response
            $formattedMatches = array_map(function($match) {
                return [
                    'id' => (int)$match['id'],
                    'match_name' => $match['match_name'],
                    'room_id' => (int)$match['room_id'],
                    'room_name' => $match['room_name'],
                    'created_by' => (int)$match['created_by'],
                    'created_by_name' => $match['created_by_name'],
                    'created_at' => $match['created_at']
                ];
            }, $matches);

            echo json_encode([
                'success' => true,
                'data' => $formattedMatches,
                'count' => count($formattedMatches)
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get matches',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy match theo ID
     */
    public function getMatch($matchId) {
        try {
            $sql = "SELECT m.*, r.room_name, u.display_name as created_by_name 
                    FROM matches m 
                    LEFT JOIN rooms r ON m.room_id = r.id 
                    LEFT JOIN users u ON m.created_by = u.id 
                    WHERE m.id = :id";
            $match = $this->db->fetchOne($sql, ['id' => $matchId]);

            if (!$match) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Match not found'
                ]);
                return;
            }

            // Get players in match
            $players = $this->db->fetchAll(
                "SELECT * FROM players WHERE match_id = :match_id ORDER BY totalScore DESC",
                ['match_id' => $matchId]
            );

            // Format response
            $formattedMatch = [
                'id' => (int)$match['id'],
                'match_name' => $match['match_name'],
                'room_id' => (int)$match['room_id'],
                'room_name' => $match['room_name'],
                'created_by' => (int)$match['created_by'],
                'created_by_name' => $match['created_by_name'],
                'players' => array_map(function($player) {
                    return [
                        'id' => (int)$player['id'],
                        'name' => $player['name'],
                        'personalSkill' => (int)$player['personalSkill'],
                        'mapReading' => (int)$player['mapReading'],
                        'teamwork' => (int)$player['teamwork'],
                        'reaction' => (int)$player['reaction'],
                        'experience' => (int)$player['experience'],
                        'position' => (int)$player['position'],
                        'totalScore' => (int)$player['totalScore'],
                        'created_by' => (int)$player['created_by'],
                        'created_at' => $player['created_at']
                    ];
                }, $players),
                'player_count' => count($players),
                'created_at' => $match['created_at']
            ];

            echo json_encode([
                'success' => true,
                'data' => $formattedMatch
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get match',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tạo match mới
     */
    public function createMatch($data) {
        try {
            // Validation
            $errors = $this->validateMatchData($data);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                return;
            }

            // Check if room exists
            $room = $this->db->fetchOne(
                "SELECT id FROM rooms WHERE id = :room_id AND is_active = 1",
                ['room_id' => $data['room_id']]
            );

            if (!$room) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Room not found or inactive'
                ]);
                return;
            }

            // Prepare data
            $matchData = [
                'match_name' => $data['match_name'],
                'room_id' => $data['room_id'],
                'created_by' => $data['created_by']
            ];

            $matchId = $this->db->insert('matches', $matchData);

            echo json_encode([
                'success' => true,
                'message' => 'Match created successfully',
                'data' => ['id' => $matchId]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create match',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Cập nhật match
     */
    public function updateMatch($matchId, $data) {
        try {
            // Check if match exists
            $existingMatch = $this->db->fetchOne(
                "SELECT id FROM matches WHERE id = :id",
                ['id' => $matchId]
            );

            if (!$existingMatch) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Match not found'
                ]);
                return;
            }

            // Prepare update data
            $updateData = [];
            if (isset($data['match_name'])) $updateData['match_name'] = $data['match_name'];

            if (empty($updateData)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No data to update'
                ]);
                return;
            }

            $rowsAffected = $this->db->update(
                'matches',
                $updateData,
                'id = :id',
                ['id' => $matchId]
            );

            echo json_encode([
                'success' => true,
                'message' => 'Match updated successfully',
                'data' => ['rows_affected' => $rowsAffected]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update match',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xóa match
     */
    public function deleteMatch($matchId) {
        try {
            // Check if match exists
            $existingMatch = $this->db->fetchOne(
                "SELECT id FROM matches WHERE id = :id",
                ['id' => $matchId]
            );

            if (!$existingMatch) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Match not found'
                ]);
                return;
            }

            $this->db->beginTransaction();

            try {
                // Delete players first (foreign key constraint)
                $this->db->delete('players', 'match_id = :match_id', ['match_id' => $matchId]);
                
                // Delete match
                $rowsAffected = $this->db->delete('matches', 'id = :id', ['id' => $matchId]);

                $this->db->commit();

                echo json_encode([
                    'success' => true,
                    'message' => 'Match deleted successfully',
                    'data' => ['rows_affected' => $rowsAffected]
                ]);

            } catch (Exception $e) {
                $this->db->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete match',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Validation cho match data
     */
    private function validateMatchData($data) {
        $errors = [];

        if (empty($data['match_name'])) {
            $errors[] = 'Match name is required';
        } elseif (strlen($data['match_name']) < 3) {
            $errors[] = 'Match name must be at least 3 characters';
        }

        if (empty($data['room_id'])) {
            $errors[] = 'Room ID is required';
        }

        if (empty($data['created_by'])) {
            $errors[] = 'Created by is required';
        }

        return $errors;
    }
}
?>
