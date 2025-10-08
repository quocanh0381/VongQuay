<?php
/**
 * Room Controller
 * Xử lý các API cho Room
 */

class RoomController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Lấy danh sách rooms
     */
    public function getRooms($params = []) {
        try {
            $sql = "SELECT r.*, u.display_name as created_by_name 
                    FROM rooms r 
                    LEFT JOIN users u ON r.created_by = u.id";
            $whereClause = [];
            $params_array = [];

            // Filter by is_active
            if (isset($params['is_active'])) {
                $whereClause[] = "r.is_active = :is_active";
                $params_array['is_active'] = $params['is_active'];
            }

            // Filter by created_by
            if (isset($params['created_by'])) {
                $whereClause[] = "r.created_by = :created_by";
                $params_array['created_by'] = $params['created_by'];
            }

            if (!empty($whereClause)) {
                $sql .= " WHERE " . implode(' AND ', $whereClause);
            }

            // Order by
            $orderBy = $params['order_by'] ?? 'created_at';
            $orderDir = $params['order_dir'] ?? 'DESC';
            $sql .= " ORDER BY r.{$orderBy} {$orderDir}";

            // Pagination
            $limit = $params['limit'] ?? 50;
            $offset = $params['offset'] ?? 0;
            $sql .= " LIMIT {$limit} OFFSET {$offset}";

            $rooms = $this->db->fetchAll($sql, $params_array);

            // Format response
            $formattedRooms = array_map(function($room) {
                return [
                    'id' => (int)$room['id'],
                    'room_name' => $room['room_name'],
                    'room_code' => $room['room_code'],
                    'max_players' => (int)$room['max_players'],
                    'is_active' => (bool)$room['is_active'],
                    'created_by' => (int)$room['created_by'],
                    'created_by_name' => $room['created_by_name'],
                    'created_at' => $room['created_at'],
                    'updated_at' => $room['updated_at']
                ];
            }, $rooms);

            echo json_encode([
                'success' => true,
                'data' => $formattedRooms,
                'count' => count($formattedRooms)
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get rooms',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy room theo ID
     */
    public function getRoom($roomId) {
        try {
            $sql = "SELECT r.*, u.display_name as created_by_name 
                    FROM rooms r 
                    LEFT JOIN users u ON r.created_by = u.id 
                    WHERE r.id = :id";
            $room = $this->db->fetchOne($sql, ['id' => $roomId]);

            if (!$room) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Room not found'
                ]);
                return;
            }

            // Get participants
            $participants = $this->db->fetchAll(
                "SELECT rp.*, u.display_name, u.username 
                 FROM room_participants rp 
                 LEFT JOIN users u ON rp.user_id = u.id 
                 WHERE rp.room_id = :room_id",
                ['room_id' => $roomId]
            );

            // Format response
            $formattedRoom = [
                'id' => (int)$room['id'],
                'room_name' => $room['room_name'],
                'room_code' => $room['room_code'],
                'max_players' => (int)$room['max_players'],
                'is_active' => (bool)$room['is_active'],
                'created_by' => (int)$room['created_by'],
                'created_by_name' => $room['created_by_name'],
                'participants' => array_map(function($p) {
                    return [
                        'id' => (int)$p['id'],
                        'user_id' => (int)$p['user_id'],
                        'username' => $p['username'],
                        'display_name' => $p['display_name'],
                        'is_room_owner' => (bool)$p['is_room_owner'],
                        'joined_at' => $p['joined_at']
                    ];
                }, $participants),
                'current_players' => count($participants),
                'created_at' => $room['created_at'],
                'updated_at' => $room['updated_at']
            ];

            echo json_encode([
                'success' => true,
                'data' => $formattedRoom
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get room',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy room theo mã phòng
     */
    public function getRoomByCode($roomCode) {
        try {
            $sql = "SELECT r.*, u.display_name as created_by_name 
                    FROM rooms r 
                    LEFT JOIN users u ON r.created_by = u.id 
                    WHERE r.room_code = :room_code";
            $room = $this->db->fetchOne($sql, ['room_code' => $roomCode]);

            if (!$room) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Room not found'
                ]);
                return;
            }

            // Get participants
            $participants = $this->db->fetchAll(
                "SELECT rp.*, u.display_name, u.username 
                 FROM room_participants rp 
                 LEFT JOIN users u ON rp.user_id = u.id 
                 WHERE rp.room_id = :room_id",
                ['room_id' => $room['id']]
            );

            // Format response
            $formattedRoom = [
                'id' => (int)$room['id'],
                'room_name' => $room['room_name'],
                'room_code' => $room['room_code'],
                'max_players' => (int)$room['max_players'],
                'is_active' => (bool)$room['is_active'],
                'created_by' => (int)$room['created_by'],
                'created_by_name' => $room['created_by_name'],
                'participants' => array_map(function($p) {
                    return [
                        'id' => (int)$p['id'],
                        'user_id' => (int)$p['user_id'],
                        'username' => $p['username'],
                        'display_name' => $p['display_name'],
                        'is_room_owner' => (bool)$p['is_room_owner'],
                        'joined_at' => $p['joined_at']
                    ];
                }, $participants),
                'current_players' => count($participants),
                'created_at' => $room['created_at'],
                'updated_at' => $room['updated_at']
            ];

            echo json_encode([
                'success' => true,
                'data' => $formattedRoom
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get room by code',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tạo room mới
     */
    public function createRoom($data) {
        try {
            // Validation
            $errors = $this->validateRoomData($data);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                return;
            }

            // Generate room code
            $roomCode = $this->generateRoomCode();

            // Check if room code exists
            while ($this->db->fetchOne("SELECT id FROM rooms WHERE room_code = :code", ['code' => $roomCode])) {
                $roomCode = $this->generateRoomCode();
            }

            // Prepare data
            $roomData = [
                'room_name' => $data['room_name'],
                'room_code' => $roomCode,
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'created_by' => $data['created_by'],
                'max_players' => $data['max_players'] ?? 10,
                'is_active' => 1
            ];

            $this->db->beginTransaction();

            try {
                $roomId = $this->db->insert('rooms', $roomData);

                // Add creator as participant
                $this->db->insert('room_participants', [
                    'room_id' => $roomId,
                    'user_id' => $data['created_by'],
                    'is_room_owner' => 1
                ]);

                $this->db->commit();

                echo json_encode([
                    'success' => true,
                    'message' => 'Room created successfully',
                    'data' => [
                        'id' => $roomId,
                        'room_code' => $roomCode,
                        'room_name' => $data['room_name']
                    ]
                ]);

            } catch (Exception $e) {
                $this->db->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create room',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tham gia phòng
     */
    public function joinRoom($data) {
        try {
            // Validation
            if (empty($data['room_code']) || empty($data['password']) || empty($data['user_id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Room code, password and user_id are required'
                ]);
                return;
            }

            // Get room
            $room = $this->db->fetchOne(
                "SELECT * FROM rooms WHERE room_code = :room_code AND is_active = 1",
                ['room_code' => $data['room_code']]
            );

            if (!$room) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Room not found or inactive'
                ]);
                return;
            }

            // Verify password
            if (!password_verify($data['password'], $room['password'])) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid room password'
                ]);
                return;
            }

            // Check if user already in room
            $existingParticipant = $this->db->fetchOne(
                "SELECT id FROM room_participants WHERE room_id = :room_id AND user_id = :user_id",
                ['room_id' => $room['id'], 'user_id' => $data['user_id']]
            );

            if ($existingParticipant) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'User already in room'
                ]);
                return;
            }

            // Check room capacity
            $participantCount = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM room_participants WHERE room_id = :room_id",
                ['room_id' => $room['id']]
            )['count'];

            if ($participantCount >= $room['max_players']) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'Room is full'
                ]);
                return;
            }

            // Add participant
            $this->db->insert('room_participants', [
                'room_id' => $room['id'],
                'user_id' => $data['user_id'],
                'is_room_owner' => 0
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Successfully joined room',
                'data' => [
                    'room_id' => $room['id'],
                    'room_name' => $room['room_name'],
                    'room_code' => $room['room_code']
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to join room',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tạo mã phòng ngẫu nhiên
     */
    private function generateRoomCode() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * Validation cho room data
     */
    private function validateRoomData($data) {
        $errors = [];

        if (empty($data['room_name'])) {
            $errors[] = 'Room name is required';
        } elseif (strlen($data['room_name']) < 3) {
            $errors[] = 'Room name must be at least 3 characters';
        }

        if (empty($data['password'])) {
            $errors[] = 'Password is required';
        } elseif (strlen($data['password']) < 4) {
            $errors[] = 'Password must be at least 4 characters';
        }

        if (empty($data['created_by'])) {
            $errors[] = 'Created by is required';
        }

        if (isset($data['max_players']) && ($data['max_players'] < 2 || $data['max_players'] > 20)) {
            $errors[] = 'Max players must be between 2 and 20';
        }

        return $errors;
    }
}
?>
