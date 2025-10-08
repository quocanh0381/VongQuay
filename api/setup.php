<?php
/**
 * Database Setup
 * Import database và tạo dữ liệu mẫu
 */

// Include database files
require_once 'config/database.php';
require_once 'config/Database.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Setup</h1>";

try {
    $db = Database::getInstance();
    
    if (!$db->isConnected()) {
        throw new Exception("Database connection failed");
    }
    
    echo "<p style='color: green;'>✓ Database connected successfully!</p>";
    
    // Check if tables exist
    echo "<h2>Checking Tables</h2>";
    
    $tables = ['users', 'rooms', 'room_participants', 'matches', 'players'];
    
    foreach ($tables as $table) {
        $result = $db->fetchOne("SHOW TABLES LIKE ?", [$table]);
        if ($result) {
            echo "<p style='color: green;'>✓ Table '{$table}' exists</p>";
        } else {
            echo "<p style='color: red;'>✗ Table '{$table}' does not exist</p>";
        }
    }
    
    // Check data
    echo "<h2>Checking Data</h2>";
    
    $usersCount = $db->fetchOne("SELECT COUNT(*) as count FROM users")['count'];
    echo "<p>Users: {$usersCount}</p>";
    
    $roomsCount = $db->fetchOne("SELECT COUNT(*) as count FROM rooms")['count'];
    echo "<p>Rooms: {$roomsCount}</p>";
    
    $matchesCount = $db->fetchOne("SELECT COUNT(*) as count FROM matches")['count'];
    echo "<p>Matches: {$matchesCount}</p>";
    
    $playersCount = $db->fetchOne("SELECT COUNT(*) as count FROM players")['count'];
    echo "<p>Players: {$playersCount}</p>";
    
    // Test API endpoints
    echo "<h2>Testing API Endpoints</h2>";
    
    $baseUrl = 'http://localhost/vongquay/api';
    
    // Test health endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/health');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "<p style='color: green;'>✓ API health check passed</p>";
    } else {
        echo "<p style='color: red;'>✗ API health check failed (HTTP {$httpCode})</p>";
    }
    
    // Test users endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/users');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "<p style='color: green;'>✓ Users API working</p>";
    } else {
        echo "<p style='color: red;'>✗ Users API failed (HTTP {$httpCode})</p>";
    }
    
    // Test rooms endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/rooms');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "<p style='color: green;'>✓ Rooms API working</p>";
    } else {
        echo "<p style='color: red;'>✗ Rooms API failed (HTTP {$httpCode})</p>";
    }
    
    echo "<h2>Setup Complete!</h2>";
    echo "<p>You can now test the API endpoints:</p>";
    echo "<ul>";
    echo "<li><a href='{$baseUrl}/health' target='_blank'>Health Check</a></li>";
    echo "<li><a href='{$baseUrl}/users' target='_blank'>Users API</a></li>";
    echo "<li><a href='{$baseUrl}/rooms' target='_blank'>Rooms API</a></li>";
    echo "<li><a href='{$baseUrl}/matches' target='_blank'>Matches API</a></li>";
    echo "<li><a href='{$baseUrl}/players' target='_blank'>Players API</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
