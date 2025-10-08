<?php
/**
 * Test Database Connection
 * Kiểm tra kết nối database
 */

// Include database files
require_once 'config/database.php';
require_once 'config/Database.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Test</h1>";

try {
    // Test DatabaseConfig
    echo "<h2>1. Testing DatabaseConfig</h2>";
    $config = DatabaseConfig::getConfig();
    echo "<p>Config: " . json_encode($config) . "</p>";
    
    $dsn = DatabaseConfig::getDSN();
    echo "<p>DSN: " . $dsn . "</p>";

    // Test Database connection
    echo "<h2>2. Testing Database Connection</h2>";
    $db = Database::getInstance();
    
    if ($db->isConnected()) {
        echo "<p style='color: green;'>✓ Database connected successfully!</p>";
        
        // Test health check
        echo "<h2>3. Testing Health Check</h2>";
        $health = $db->healthCheck();
        if ($health) {
            echo "<p style='color: green;'>✓ Health check passed!</p>";
        } else {
            echo "<p style='color: red;'>✗ Health check failed!</p>";
        }
        
        // Test simple query
        echo "<h2>4. Testing Simple Query</h2>";
        $result = $db->fetchOne("SELECT NOW() as current_time");
        echo "<p>Current time: " . $result['current_time'] . "</p>";
        
        // Test users table
        echo "<h2>5. Testing Users Table</h2>";
        $users = $db->fetchAll("SELECT COUNT(*) as count FROM users");
        echo "<p>Users count: " . $users[0]['count'] . "</p>";
        
        // Test rooms table
        echo "<h2>6. Testing Rooms Table</h2>";
        $rooms = $db->fetchAll("SELECT COUNT(*) as count FROM rooms");
        echo "<p>Rooms count: " . $rooms[0]['count'] . "</p>";
        
        // Test matches table
        echo "<h2>7. Testing Matches Table</h2>";
        $matches = $db->fetchAll("SELECT COUNT(*) as count FROM matches");
        echo "<p>Matches count: " . $matches[0]['count'] . "</p>";
        
        // Test players table
        echo "<h2>8. Testing Players Table</h2>";
        $players = $db->fetchAll("SELECT COUNT(*) as count FROM players");
        echo "<p>Players count: " . $players[0]['count'] . "</p>";
        
    } else {
        echo "<p style='color: red;'>✗ Database connection failed!</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>Test Complete</h2>";
?>
