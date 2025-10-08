<?php
require_once 'models/User.php';
require_once 'models/Match.php';
require_once 'models/Player.php';

class MatchController {
    private $userModel;
    private $gameModel;
    private $playerModel;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        
        $this->userModel = new User($db);
        $this->gameModel = new GameMatch($db);
        $this->playerModel = new Player($db);
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $matches = $this->gameModel->getMatches();
        include 'views/matches.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $match_name = $_POST['match_name'] ?? '';
            $room_id = $_POST['room_id'] ?? null;
            
            if (!empty($match_name)) {
                // Tạo user tạm thời
                $this->userModel->username = 'user_' . time();
                $this->userModel->password = 'temp';
                $this->userModel->display_name = 'Match Creator';
                
                if ($this->userModel->register()) {
                    $user_id = $this->userModel->getLastInsertId();
                    
                    $this->gameModel->match_name = $match_name;
                    $this->gameModel->room_id = $room_id ?: null;
                    $this->gameModel->created_by = $user_id;
                    
                    if ($this->gameModel->create()) {
                        $match_id = $this->gameModel->getLastInsertId();
                        // Chuyển đến form thêm người chơi
                        header('Location: index.php?action=add_player&match_id=' . $match_id);
                        exit;
                    } else {
                        $error = "Có lỗi xảy ra khi tạo match!";
                    }
                } else {
                    $error = "Có lỗi xảy ra khi tạo tài khoản!";
                }
            } else {
                $error = "Vui lòng nhập tên match!";
            }
        }
        
        include 'views/create_match.php';
    }

    public function addPlayer($match_id) {
        $match = $this->gameModel->getMatchById($match_id);
        if (!$match) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $personalSkill = $_POST['personalSkill'] ?? 5;
            $mapReading = $_POST['mapReading'] ?? 5;
            $teamwork = $_POST['teamwork'] ?? 5;
            $reaction = $_POST['reaction'] ?? 5;
            $experience = $_POST['experience'] ?? 5;
            $position = $_POST['position'] ?? 5;
            
            if (!empty($name)) {
                // Tạo user tạm thời cho player
                $this->userModel->username = 'player_' . time();
                $this->userModel->password = 'temp';
                $this->userModel->display_name = $name;
                
                if ($this->userModel->register()) {
                    $user_id = $this->userModel->getLastInsertId();
                    
                    $totalScore = $personalSkill + $mapReading + $teamwork + $reaction + $experience;
                    
                    $this->playerModel->match_id = $match_id;
                    $this->playerModel->name = $name;
                    $this->playerModel->personalSkill = $personalSkill;
                    $this->playerModel->mapReading = $mapReading;
                    $this->playerModel->teamwork = $teamwork;
                    $this->playerModel->reaction = $reaction;
                    $this->playerModel->experience = $experience;
                    $this->playerModel->position = $position;
                    $this->playerModel->totalScore = $totalScore;
                    $this->playerModel->created_by = $user_id;
                    
                    if ($this->playerModel->create()) {
                        $success = "Thêm người chơi thành công!";
                        // Quay lại trang match để tiếp tục thêm hoặc chia team
                        header('Location: index.php?action=match&id=' . $match_id);
                        exit;
                    } else {
                        $error = "Có lỗi xảy ra khi thêm người chơi!";
                    }
                } else {
                    $error = "Có lỗi xảy ra khi tạo tài khoản!";
                }
            } else {
                $error = "Vui lòng nhập tên người chơi!";
            }
        }
        
        include 'views/add_player.php';
    }

    public function view($match_id) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $match = $this->gameModel->getMatchById($match_id);
        if (!$match) {
            header('Location: index.php?action=matches');
            exit;
        }
        
        $players = $this->playerModel->getPlayersByMatch($match_id);
        include 'views/match_detail.php';
    }


    public function balanceTeams($match_id) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $match = $this->gameModel->getMatchById($match_id);
        if (!$match) {
            header('Location: index.php?action=matches');
            exit;
        }
        
        $players = $this->playerModel->getPlayersByMatch($match_id);
        
        if (count($players) < 2) {
            $error = "Cần ít nhất 2 player để chia team!";
        } else {
            $teams = $this->playerModel->balanceTeams($players);
        }
        
        include 'views/balance_teams.php';
    }

    public function delete($match_id) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $this->gameModel->delete($match_id);
        header('Location: index.php?action=matches');
        exit;
    }
}
?>
