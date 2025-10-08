<?php
/**
 * Application Configuration
 * Cấu hình chung cho ứng dụng
 */

// Bật error reporting cho development
if (defined('DEVELOPMENT') && DEVELOPMENT) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Cấu hình session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.gc_maxlifetime', 3600); // 1 hour

// Cấu hình timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Cấu hình memory và execution time
ini_set('memory_limit', '128M');
ini_set('max_execution_time', 30);

// Cấu hình upload
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

// Constants
define('APP_NAME', 'VongQuay');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'https://your-domain.com'); // Thay đổi thành domain thực tế

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Pagination
define('ITEMS_PER_PAGE', 10);

// File paths
define('ROOT_PATH', dirname(__DIR__));
define('VIEWS_PATH', ROOT_PATH . '/views');
define('MODELS_PATH', ROOT_PATH . '/models');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Helper functions
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function validateCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function logError($message, $context = []) {
    $logMessage = date('Y-m-d H:i:s') . ' - ' . $message;
    if (!empty($context)) {
        $logMessage .= ' - Context: ' . json_encode($context);
    }
    error_log($logMessage);
}

// Auto-load classes
spl_autoload_register(function ($class) {
    $paths = [
        MODELS_PATH . '/' . $class . '.php',
        CONTROLLERS_PATH . '/' . $class . '.php',
        CONFIG_PATH . '/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
