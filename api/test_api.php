<?php
/**
 * Test API Endpoints
 * Kiểm tra các API endpoints
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>API Endpoints Test</h1>";

// Test data
$testData = [
    'user' => [
        'username' => 'testuser',
        'password' => '123456',
        'display_name' => 'Test User',
        'email' => 'test@example.com'
    ],
    'room' => [
        'room_name' => 'Test Room',
        'password' => '123456',
        'created_by' => 1,
        'max_players' => 10
    ],
    'match' => [
        'match_name' => 'Test Match',
        'room_id' => 1,
        'created_by' => 1
    ],
    'player' => [
        'match_id' => 1,
        'name' => 'Test Player',
        'personalSkill' => 4,
        'mapReading' => 3,
        'teamwork' => 5,
        'reaction' => 4,
        'experience' => 4,
        'position' => 1,
        'created_by' => 1
    ]
];

// Function to make API call
function makeApiCall($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($data && ($method === 'POST' || $method === 'PUT')) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

// Test endpoints
$baseUrl = 'http://localhost/vongquay/api';

echo "<h2>1. Testing Health Check</h2>";
$result = makeApiCall($baseUrl . '/health');
echo "<p>HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";
if ($result['error']) {
    echo "<p style='color: red;'>Error: " . $result['error'] . "</p>";
}

echo "<h2>2. Testing Users API</h2>";
$result = makeApiCall($baseUrl . '/users');
echo "<p>GET /users - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>3. Testing Rooms API</h2>";
$result = makeApiCall($baseUrl . '/rooms');
echo "<p>GET /rooms - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>4. Testing Matches API</h2>";
$result = makeApiCall($baseUrl . '/matches');
echo "<p>GET /matches - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>5. Testing Players API</h2>";
$result = makeApiCall($baseUrl . '/players');
echo "<p>GET /players - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>6. Testing Create User</h2>";
$result = makeApiCall($baseUrl . '/users', 'POST', $testData['user']);
echo "<p>POST /users - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>7. Testing Create Room</h2>";
$result = makeApiCall($baseUrl . '/rooms', 'POST', $testData['room']);
echo "<p>POST /rooms - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>8. Testing Create Match</h2>";
$result = makeApiCall($baseUrl . '/matches', 'POST', $testData['match']);
echo "<p>POST /matches - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>9. Testing Create Player</h2>";
$result = makeApiCall($baseUrl . '/players', 'POST', $testData['player']);
echo "<p>POST /players - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>10. Testing Team Creation</h2>";
$result = makeApiCall($baseUrl . '/teams/skill/1', 'POST');
echo "<p>POST /teams/skill/1 - HTTP Code: " . $result['http_code'] . "</p>";
echo "<p>Response: " . $result['response'] . "</p>";

echo "<h2>Test Complete</h2>";
?>
