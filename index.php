<?php
// Start session
session_start();

// Include models and controllers
require_once 'config/database.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/RoomController.php';
require_once 'controllers/MatchController.php';

// Get action from URL
$action = $_GET['action'] ?? 'home';

// Initialize controllers
$homeController = new HomeController();
$roomController = new RoomController();
$matchController = new MatchController();

// Route requests
switch ($action) {
    case 'home':
        $homeController->index();
        break;
        
        
    case 'create_room':
        $homeController->createRoom();
        break;
        
    case 'join_room':
        $homeController->joinRoom();
        break;
        
    case 'room':
        $room_id = $_GET['id'] ?? null;
        $player_name = $_GET['player'] ?? '';
        if ($room_id) {
            $homeController->viewRoom($room_id, $player_name);
        } else {
            header('Location: index.php');
        }
        break;
        
    case 'add_member':
        $room_id = $_GET['room_id'] ?? null;
        if ($room_id) {
            $homeController->addMember($room_id);
        } else {
            header('Location: index.php');
        }
        break;
        
    case 'balance_teams':
        $room_id = $_GET['room_id'] ?? null;
        if ($room_id) {
            $homeController->balanceTeams($room_id);
        } else {
            header('Location: index.php');
        }
        break;
        
    case 'show_teams':
        $room_id = $_GET['room_id'] ?? null;
        if ($room_id) {
            $homeController->showTeams($room_id);
        } else {
            header('Location: index.php');
        }
        break;
        
    case 'edit_player':
        $player_id = $_GET['player_id'] ?? null;
        $room_id = $_GET['room_id'] ?? null;
        if ($player_id && $room_id) {
            $homeController->editPlayer($player_id, $room_id);
        } else {
            header('Location: index.php');
        }
        break;
        
    case 'delete_player':
        $player_id = $_GET['player_id'] ?? null;
        $room_id = $_GET['room_id'] ?? null;
        if ($player_id && $room_id) {
            $homeController->deletePlayer($player_id, $room_id);
        } else {
            header('Location: index.php');
        }
        break;
        
    case 'matches':
        $matchController->index();
        break;
        
    case 'create_match':
        $matchController->create();
        break;
        
    case 'match':
        $match_id = $_GET['id'] ?? null;
        if ($match_id) {
            $matchController->view($match_id);
        } else {
            header('Location: index.php?action=matches');
        }
        break;
        
    case 'add_player':
        $match_id = $_GET['match_id'] ?? null;
        if ($match_id) {
            $matchController->addPlayer($match_id);
        } else {
            header('Location: index.php?action=matches');
        }
        break;
        
        
    case 'delete_match':
        $match_id = $_GET['id'] ?? null;
        if ($match_id) {
            $matchController->delete($match_id);
        } else {
            header('Location: index.php?action=matches');
        }
        break;
        
    default:
        // Redirect to home
        header('Location: index.php?action=home');
        break;
}
?>
