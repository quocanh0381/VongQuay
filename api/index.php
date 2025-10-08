<?php
/**
 * API Main Entry Point
 * Xử lý tất cả API requests
 */

// Cấu hình headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Xử lý preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include các file cần thiết
require_once 'config/Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/RoomController.php';
require_once 'controllers/MatchController.php';
require_once 'controllers/PlayerController.php';
require_once 'controllers/TeamController.php';

// Khởi tạo database
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ]);
    exit();
}

// Lấy request method và URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/vongquay/api', '', $uri);

// Lấy request data
$input = json_decode(file_get_contents('php://input'), true);
$params = $_GET;

// Router
try {
    switch ($path) {
        // Health check
        case '/health':
            if ($method === 'GET') {
                $health = $db->healthCheck();
                echo json_encode([
                    'success' => $health,
                    'message' => $health ? 'API is healthy' : 'API is unhealthy',
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        // User routes
        case '/users':
            $controller = new UserController($db);
            if ($method === 'GET') {
                $controller->getUsers($params);
            } elseif ($method === 'POST') {
                $controller->createUser($input);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case (preg_match('/^\/users\/(\d+)$/', $path, $matches) ? true : false):
            $userId = $matches[1];
            $controller = new UserController($db);
            if ($method === 'GET') {
                $controller->getUser($userId);
            } elseif ($method === 'PUT') {
                $controller->updateUser($userId, $input);
            } elseif ($method === 'DELETE') {
                $controller->deleteUser($userId);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        // Room routes
        case '/rooms':
            $controller = new RoomController($db);
            if ($method === 'GET') {
                $controller->getRooms($params);
            } elseif ($method === 'POST') {
                $controller->createRoom($input);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case (preg_match('/^\/rooms\/(\d+)$/', $path, $matches) ? true : false):
            $roomId = $matches[1];
            $controller = new RoomController($db);
            if ($method === 'GET') {
                $controller->getRoom($roomId);
            } elseif ($method === 'PUT') {
                $controller->updateRoom($roomId, $input);
            } elseif ($method === 'DELETE') {
                $controller->deleteRoom($roomId);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case (preg_match('/^\/rooms\/code\/([A-Z0-9]+)$/', $path, $matches) ? true : false):
            $roomCode = $matches[1];
            $controller = new RoomController($db);
            if ($method === 'GET') {
                $controller->getRoomByCode($roomCode);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case '/rooms/join':
            $controller = new RoomController($db);
            if ($method === 'POST') {
                $controller->joinRoom($input);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        // Match routes
        case '/matches':
            $controller = new MatchController($db);
            if ($method === 'GET') {
                $controller->getMatches($params);
            } elseif ($method === 'POST') {
                $controller->createMatch($input);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case (preg_match('/^\/matches\/(\d+)$/', $path, $matches) ? true : false):
            $matchId = $matches[1];
            $controller = new MatchController($db);
            if ($method === 'GET') {
                $controller->getMatch($matchId);
            } elseif ($method === 'PUT') {
                $controller->updateMatch($matchId, $input);
            } elseif ($method === 'DELETE') {
                $controller->deleteMatch($matchId);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        // Player routes
        case '/players':
            $controller = new PlayerController($db);
            if ($method === 'GET') {
                $controller->getPlayers($params);
            } elseif ($method === 'POST') {
                $controller->createPlayer($input);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        // Team routes
        case (preg_match('/^\/teams\/skill\/(\d+)$/', $path, $matches) ? true : false):
            $matchId = $matches[1];
            $controller = new TeamController($db);
            if ($method === 'POST') {
                $controller->createSkillTeams($matchId);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case (preg_match('/^\/teams\/random\/(\d+)$/', $path, $matches) ? true : false):
            $matchId = $matches[1];
            $controller = new TeamController($db);
            if ($method === 'POST') {
                $controller->createRandomTeams($matchId);
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Endpoint not found',
                'path' => $path
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error',
        'error' => $e->getMessage()
    ]);
}
?>
