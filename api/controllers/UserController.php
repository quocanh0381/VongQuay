<?php
/**
 * User Controller
 * Xử lý các API cho User
 */

class UserController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Lấy danh sách users
     */
    public function getUsers($params = []) {
        try {
            $sql = "SELECT * FROM users";
            $whereClause = [];
            $params_array = [];

            // Filter by level
            if (isset($params['level'])) {
                $whereClause[] = "level = :level";
                $params_array['level'] = $params['level'];
            }

            // Filter by total_matches
            if (isset($params['min_matches'])) {
                $whereClause[] = "total_matches >= :min_matches";
                $params_array['min_matches'] = $params['min_matches'];
            }

            if (!empty($whereClause)) {
                $sql .= " WHERE " . implode(' AND ', $whereClause);
            }

            // Order by
            $orderBy = $params['order_by'] ?? 'created_at';
            $orderDir = $params['order_dir'] ?? 'DESC';
            $sql .= " ORDER BY {$orderBy} {$orderDir}";

            // Pagination
            $limit = $params['limit'] ?? 50;
            $offset = $params['offset'] ?? 0;
            $sql .= " LIMIT {$limit} OFFSET {$offset}";

            $users = $this->db->fetchAll($sql, $params_array);

            // Format response
            $formattedUsers = array_map(function($user) {
                return [
                    'id' => (int)$user['id'],
                    'username' => $user['username'],
                    'display_name' => $user['display_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'avatar' => $user['avatar'],
                    'level' => (int)$user['level'],
                    'total_matches' => (int)$user['total_matches'],
                    'win_matches' => (int)$user['win_matches'],
                    'win_rate' => $user['total_matches'] > 0 ? 
                        round(($user['win_matches'] / $user['total_matches']) * 100, 2) : 0,
                    'created_at' => $user['created_at'],
                    'updated_at' => $user['updated_at']
                ];
            }, $users);

            echo json_encode([
                'success' => true,
                'data' => $formattedUsers,
                'count' => count($formattedUsers)
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get users',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy user theo ID
     */
    public function getUser($userId) {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $user = $this->db->fetchOne($sql, ['id' => $userId]);

            if (!$user) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found'
                ]);
                return;
            }

            // Format response
            $formattedUser = [
                'id' => (int)$user['id'],
                'username' => $user['username'],
                'display_name' => $user['display_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'avatar' => $user['avatar'],
                'level' => (int)$user['level'],
                'total_matches' => (int)$user['total_matches'],
                'win_matches' => (int)$user['win_matches'],
                'win_rate' => $user['total_matches'] > 0 ? 
                    round(($user['win_matches'] / $user['total_matches']) * 100, 2) : 0,
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at']
            ];

            echo json_encode([
                'success' => true,
                'data' => $formattedUser
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get user',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tạo user mới
     */
    public function createUser($data) {
        try {
            // Validation
            $errors = $this->validateUserData($data);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                return;
            }

            // Check if username exists
            $existingUser = $this->db->fetchOne(
                "SELECT id FROM users WHERE username = :username",
                ['username' => $data['username']]
            );

            if ($existingUser) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'Username already exists'
                ]);
                return;
            }

            // Prepare data
            $userData = [
                'username' => $data['username'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'display_name' => $data['display_name'] ?? $data['username'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'avatar' => $data['avatar'] ?? null,
                'level' => $data['level'] ?? 1,
                'total_matches' => 0,
                'win_matches' => 0
            ];

            $userId = $this->db->insert('users', $userData);

            echo json_encode([
                'success' => true,
                'message' => 'User created successfully',
                'data' => ['id' => $userId]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Cập nhật user
     */
    public function updateUser($userId, $data) {
        try {
            // Check if user exists
            $existingUser = $this->db->fetchOne(
                "SELECT id FROM users WHERE id = :id",
                ['id' => $userId]
            );

            if (!$existingUser) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found'
                ]);
                return;
            }

            // Prepare update data
            $updateData = [];
            if (isset($data['display_name'])) $updateData['display_name'] = $data['display_name'];
            if (isset($data['email'])) $updateData['email'] = $data['email'];
            if (isset($data['phone'])) $updateData['phone'] = $data['phone'];
            if (isset($data['avatar'])) $updateData['avatar'] = $data['avatar'];
            if (isset($data['level'])) $updateData['level'] = $data['level'];
            if (isset($data['password'])) {
                $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            if (empty($updateData)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No data to update'
                ]);
                return;
            }

            $rowsAffected = $this->db->update(
                'users',
                $updateData,
                'id = :id',
                ['id' => $userId]
            );

            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => ['rows_affected' => $rowsAffected]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xóa user
     */
    public function deleteUser($userId) {
        try {
            // Check if user exists
            $existingUser = $this->db->fetchOne(
                "SELECT id FROM users WHERE id = :id",
                ['id' => $userId]
            );

            if (!$existingUser) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found'
                ]);
                return;
            }

            $rowsAffected = $this->db->delete('users', 'id = :id', ['id' => $userId]);

            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully',
                'data' => ['rows_affected' => $rowsAffected]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Validation cho user data
     */
    private function validateUserData($data) {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = 'Username is required';
        } elseif (strlen($data['username']) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        }

        if (empty($data['password'])) {
            $errors[] = 'Password is required';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (isset($data['phone']) && !preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $errors[] = 'Invalid phone format';
        }

        return $errors;
    }
}
?>
