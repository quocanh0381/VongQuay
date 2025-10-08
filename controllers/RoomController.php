<?php
require_once 'models/User.php';
require_once 'models/Match.php';

class RoomController {
    private $userModel;
    private $gameModel;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        
        $this->userModel = new User($db);
        $this->gameModel = new GameMatch($db);
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
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $match_name = $_POST['match_name'] ?? '';
            
            if (!empty($match_name)) {
                $this->gameModel->match_name = $match_name;
                $this->gameModel->created_by = $_SESSION['user_id'];
                
                if ($this->gameModel->create()) {
                    $success = "Tạo match thành công!";
                    header('Location: index.php?action=match&id=' . $this->gameModel->getLastInsertId());
                    exit;
                } else {
                    $error = "Có lỗi xảy ra khi tạo match!";
                }
            } else {
                $error = "Vui lòng nhập tên match!";
            }
        }
        
        include 'views/create_match.php';
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
        
        include 'views/match_detail.php';
    }
}
?>
