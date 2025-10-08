<?php
require_once 'models/User.php';
require_once 'models/Room.php';
require_once 'models/Match.php';
require_once 'models/Player.php';

class HomeController {
    private $userModel;
    private $roomModel;
    private $gameModel;
    private $playerModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        
        $this->userModel = new User($db);
        $this->roomModel = new Room($db);
        $this->gameModel = new GameMatch($db);
        $this->playerModel = new Player($db);
    }

    public function index() {
        // Lấy danh sách phòng gần đây
        $rooms = $this->roomModel->getRooms();
        
        // Hiển thị trang chủ
        include 'views/home.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (!empty($username) && !empty($password)) {
                $user = $this->userModel->login($username, $password);
                
                if ($user) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['display_name'] = $user['display_name'];
                    
                    header('Location: index.php?action=home');
                    exit;
                } else {
                    $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
                }
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            }
        }
        
        include 'views/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $display_name = $_POST['display_name'] ?? '';
            
            if (!empty($username) && !empty($password) && !empty($display_name)) {
                // Kiểm tra username đã tồn tại
                if ($this->userModel->checkUsernameExists($username)) {
                    $error = "Tên đăng nhập đã tồn tại!";
                } else {
                    $this->userModel->username = $username;
                    $this->userModel->password = $password;
                    $this->userModel->display_name = $display_name;
                    
                    if ($this->userModel->register()) {
                        $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                    } else {
                        $error = "Có lỗi xảy ra khi đăng ký!";
                    }
                }
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin bắt buộc!";
            }
        }
        
        include 'views/register.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    public function createRoom() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $room_name = $_POST['room_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $max_players = $_POST['max_players'] ?? 10;
            $player_name = $_POST['player_name'] ?? '';
            $display_name = $_POST['display_name'] ?? '';
            
            if (!empty($room_name) && !empty($password) && !empty($player_name) && !empty($display_name)) {
                // Tạo user mới
                $this->userModel->username = 'user_' . time();
                $this->userModel->password = 'temp';
                $this->userModel->display_name = $display_name;
                
                if ($this->userModel->register()) {
                    $user_id = $this->userModel->getLastInsertId();
                    
                    // Tạo phòng
                    $this->roomModel->room_name = $room_name;
                    $this->roomModel->room_code = $this->roomModel->generateRoomCode();
                    $this->roomModel->password = $password;
                    $this->roomModel->created_by = $user_id;
                    $this->roomModel->max_players = $max_players;
                    $this->roomModel->is_active = 1;
                    
                    if ($this->roomModel->create()) {
                        $room_id = $this->roomModel->getLastInsertId();
                        $success = "Tạo phòng thành công! Mã phòng: " . $this->roomModel->room_code;
                        header('Location: index.php?action=room&id=' . $room_id . '&player=' . urlencode($player_name));
                        exit;
                    } else {
                        $error = "Có lỗi xảy ra khi tạo phòng!";
                    }
                } else {
                    $error = "Có lỗi xảy ra khi tạo tài khoản!";
                }
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            }
        }
        
        include 'views/create_room.php';
    }

    public function joinRoom() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $room_code = $_POST['room_code'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (!empty($room_code) && !empty($password)) {
                $room = $this->roomModel->joinRoom($room_code, $password);
                
                if ($room) {
                    $success = "Tham gia phòng thành công!";
                    header('Location: index.php?action=room&id=' . $room['id']);
                    exit;
                } else {
                    $error = "Mã phòng hoặc mật khẩu không đúng!";
                }
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            }
        }
        
        include 'views/join_room.php';
    }

    public function viewRoom($room_id, $player_name = '') {
        $room = $this->roomModel->getRoomById($room_id);
        if (!$room) {
            header('Location: index.php');
            exit;
        }
        
        // Lấy thông tin người tạo phòng
        $creator = $this->userModel->getUserById($room['created_by']);
        $room['creator_name'] = $creator ? $creator['display_name'] : 'Unknown';
        
        // Lấy danh sách người chơi trong phòng
        $players = $this->roomModel->getPlayersByRoom($room_id);
        
        // Lấy thông tin người tạo cho mỗi player
        foreach ($players as &$player) {
            $player_creator = $this->userModel->getUserById($player['created_by']);
            $player['creator_name'] = $player_creator ? $player_creator['display_name'] : 'Unknown';
        }
        
        include 'views/room_detail.php';
    }

    public function addMember($room_id) {
        $room = $this->roomModel->getRoomById($room_id);
        if (!$room) {
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
            $position = $_POST['position'] ?? 1;
            
            if (!empty($name) && !empty($position)) {
                // Tạo user tạm thời cho player
                $this->userModel->username = 'player_' . time();
                $this->userModel->password = 'temp';
                $this->userModel->display_name = $name;
                
                if ($this->userModel->register()) {
                    $user_id = $this->userModel->getLastInsertId();
                    
                    // Tạo match tạm thời cho phòng nếu chưa có
                    $this->gameModel->match_name = 'Match cho ' . $room['room_name'];
                    $this->gameModel->room_id = $room_id;
                    $this->gameModel->created_by = $user_id;
                    
                    if ($this->gameModel->create()) {
                        $match_id = $this->gameModel->getLastInsertId();
                        
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
                            $success = "Thêm thành viên thành công!";
                            header('Location: index.php?action=room&id=' . $room_id);
                            exit;
                        } else {
                            $error = "Có lỗi xảy ra khi thêm thành viên!";
                        }
                    } else {
                        $error = "Có lỗi xảy ra khi tạo match!";
                    }
                } else {
                    $error = "Có lỗi xảy ra khi tạo tài khoản!";
                }
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            }
        }
        
        include 'views/add_member.php';
    }

    public function balanceTeams($room_id) {
        $room = $this->roomModel->getRoomById($room_id);
        if (!$room) {
            header('Location: index.php');
            exit;
        }

        // Lấy danh sách người chơi trong phòng
        $players = $this->roomModel->getPlayersByRoom($room_id);
        
        if (count($players) < $room['max_players']) {
            $error = "Cần đủ " . $room['max_players'] . " người chơi để chia team!";
            header('Location: index.php?action=room&id=' . $room_id . '&error=' . urlencode($error));
            exit;
        }

        // Thuật toán chia team cân bằng
        $teams = $this->calculateBalancedTeams($players);
        
        // Lưu kết quả chia team vào session hoặc database
        $_SESSION['balanced_teams'] = $teams;
        $_SESSION['room_id'] = $room_id;
        
        header('Location: index.php?action=show_teams&room_id=' . $room_id);
        exit;
    }

    private function calculateBalancedTeams($players) {
        // Sắp xếp người chơi theo điểm số giảm dần
        usort($players, function($a, $b) {
            return $b['totalScore'] - $a['totalScore'];
        });

        $team1 = [];
        $team2 = [];
        $team1Score = 0;
        $team2Score = 0;

        // Chia người chơi vào 2 team
        foreach ($players as $player) {
            if ($team1Score <= $team2Score) {
                $team1[] = $player;
                $team1Score += $player['totalScore'];
            } else {
                $team2[] = $player;
                $team2Score += $player['totalScore'];
            }
        }

        // Tối ưu hóa để cân bằng hơn
        $this->optimizeTeamBalance($team1, $team2, $team1Score, $team2Score);

        return [
            'team1' => $team1,
            'team2' => $team2,
            'team1Score' => array_sum(array_column($team1, 'totalScore')),
            'team2Score' => array_sum(array_column($team2, 'totalScore'))
        ];
    }

    private function optimizeTeamBalance(&$team1, &$team2, &$team1Score, &$team2Score) {
        $maxIterations = 10;
        $iteration = 0;
        
        while ($iteration < $maxIterations) {
            $bestSwap = null;
            $bestImprovement = 0;
            
            // Tìm cặp người chơi tốt nhất để swap
            foreach ($team1 as $i => $player1) {
                foreach ($team2 as $j => $player2) {
                    $newTeam1Score = $team1Score - $player1['totalScore'] + $player2['totalScore'];
                    $newTeam2Score = $team2Score - $player2['totalScore'] + $player1['totalScore'];
                    
                    $currentDiff = abs($team1Score - $team2Score);
                    $newDiff = abs($newTeam1Score - $newTeam2Score);
                    $improvement = $currentDiff - $newDiff;
                    
                    if ($improvement > $bestImprovement) {
                        $bestImprovement = $improvement;
                        $bestSwap = ['team1_index' => $i, 'team2_index' => $j];
                    }
                }
            }
            
            // Thực hiện swap nếu có cải thiện
            if ($bestSwap && $bestImprovement > 0) {
                $temp = $team1[$bestSwap['team1_index']];
                $team1[$bestSwap['team1_index']] = $team2[$bestSwap['team2_index']];
                $team2[$bestSwap['team2_index']] = $temp;
                
                $team1Score = array_sum(array_column($team1, 'totalScore'));
                $team2Score = array_sum(array_column($team2, 'totalScore'));
            } else {
                break;
            }
            
            $iteration++;
        }
    }

    public function showTeams($room_id) {
        if (!isset($_SESSION['balanced_teams']) || $_SESSION['room_id'] != $room_id) {
            header('Location: index.php?action=room&id=' . $room_id);
            exit;
        }

        $room = $this->roomModel->getRoomById($room_id);
        $teams = $_SESSION['balanced_teams'];
        
        include 'views/team_balance.php';
    }

    public function editPlayer($player_id, $room_id) {
        $room = $this->roomModel->getRoomById($room_id);
        if (!$room) {
            header('Location: index.php');
            exit;
        }

        // Lấy thông tin player hiện tại
        $player = $this->playerModel->getPlayerById($player_id);
        if (!$player) {
            header('Location: index.php?action=room&id=' . $room_id);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $personalSkill = $_POST['personalSkill'] ?? 5;
            $mapReading = $_POST['mapReading'] ?? 5;
            $teamwork = $_POST['teamwork'] ?? 5;
            $reaction = $_POST['reaction'] ?? 5;
            $experience = $_POST['experience'] ?? 5;
            $position = $_POST['position'] ?? 1;

            if (!empty($name)) {
                $totalScore = $personalSkill + $mapReading + $teamwork + $reaction + $experience;

                $this->playerModel->id = $player_id;
                $this->playerModel->name = $name;
                $this->playerModel->personalSkill = $personalSkill;
                $this->playerModel->mapReading = $mapReading;
                $this->playerModel->teamwork = $teamwork;
                $this->playerModel->reaction = $reaction;
                $this->playerModel->experience = $experience;
                $this->playerModel->position = $position;
                $this->playerModel->totalScore = $totalScore;

                if ($this->playerModel->update()) {
                    $success = "Cập nhật thông tin thành công!";
                    header('Location: index.php?action=room&id=' . $room_id);
                    exit;
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật!";
                }
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            }
        }

        include 'views/edit_player.php';
    }

    public function deletePlayer($player_id, $room_id) {
        $room = $this->roomModel->getRoomById($room_id);
        if (!$room) {
            header('Location: index.php');
            exit;
        }

        $this->playerModel->id = $player_id;
        if ($this->playerModel->delete()) {
            $success = "Xóa người chơi thành công!";
        } else {
            $error = "Có lỗi xảy ra khi xóa người chơi!";
        }

        header('Location: index.php?action=room&id=' . $room_id);
        exit;
    }
}
?>
