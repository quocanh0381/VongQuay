<?php
/**
 * Team Controller
 * Xử lý các API cho Team creation
 */

class TeamController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Tạo team theo kỹ năng
     */
    public function createSkillTeams($matchId) {
        try {
            // Get players in match
            $players = $this->db->fetchAll(
                "SELECT * FROM players WHERE match_id = :match_id ORDER BY totalScore DESC",
                ['match_id' => $matchId]
            );

            if (count($players) < 2) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Need at least 2 players to create teams'
                ]);
                return;
            }

            // Sort players by total score (descending)
            $sortedPlayers = $players;

            // Create teams
            $team1 = [];
            $team2 = [];

            for ($i = 0; $i < count($sortedPlayers); $i++) {
                if ($i % 2 === 0) {
                    $team1[] = $sortedPlayers[$i];
                } else {
                    $team2[] = $sortedPlayers[$i];
                }
            }

            // Calculate team scores
            $team1Score = array_sum(array_column($team1, 'totalScore'));
            $team2Score = array_sum(array_column($team2, 'totalScore'));

            // Format response
            $teams = [
                [
                    'id' => 1,
                    'name' => 'Team 1',
                    'players' => array_map(function($player) {
                        return [
                            'id' => (int)$player['id'],
                            'name' => $player['name'],
                            'personalSkill' => (int)$player['personalSkill'],
                            'mapReading' => (int)$player['mapReading'],
                            'teamwork' => (int)$player['teamwork'],
                            'reaction' => (int)$player['reaction'],
                            'experience' => (int)$player['experience'],
                            'position' => (int)$player['position'],
                            'totalScore' => (int)$player['totalScore']
                        ];
                    }, $team1),
                    'totalScore' => $team1Score,
                    'playerCount' => count($team1)
                ],
                [
                    'id' => 2,
                    'name' => 'Team 2',
                    'players' => array_map(function($player) {
                        return [
                            'id' => (int)$player['id'],
                            'name' => $player['name'],
                            'personalSkill' => (int)$player['personalSkill'],
                            'mapReading' => (int)$player['mapReading'],
                            'teamwork' => (int)$player['teamwork'],
                            'reaction' => (int)$player['reaction'],
                            'experience' => (int)$player['experience'],
                            'position' => (int)$player['position'],
                            'totalScore' => (int)$player['totalScore']
                        ];
                    }, $team2),
                    'totalScore' => $team2Score,
                    'playerCount' => count($team2)
                ]
            ];

            // Calculate balance
            $balance = abs($team1Score - $team2Score);
            $isBalanced = $balance <= 5; // Consider balanced if difference <= 5

            echo json_encode([
                'success' => true,
                'message' => 'Skill-based teams created successfully',
                'data' => [
                    'teams' => $teams,
                    'balance' => $balance,
                    'isBalanced' => $isBalanced,
                    'algorithm' => 'skill_based',
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create skill teams',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tạo team ngẫu nhiên
     */
    public function createRandomTeams($matchId) {
        try {
            // Get players in match
            $players = $this->db->fetchAll(
                "SELECT * FROM players WHERE match_id = :match_id",
                ['match_id' => $matchId]
            );

            if (count($players) < 2) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Need at least 2 players to create teams'
                ]);
                return;
            }

            // Shuffle players randomly
            $shuffledPlayers = $players;
            shuffle($shuffledPlayers);

            // Create teams
            $team1 = [];
            $team2 = [];

            for ($i = 0; $i < count($shuffledPlayers); $i++) {
                if ($i % 2 === 0) {
                    $team1[] = $shuffledPlayers[$i];
                } else {
                    $team2[] = $shuffledPlayers[$i];
                }
            }

            // Calculate team scores
            $team1Score = array_sum(array_column($team1, 'totalScore'));
            $team2Score = array_sum(array_column($team2, 'totalScore'));

            // Format response
            $teams = [
                [
                    'id' => 1,
                    'name' => 'Team 1',
                    'players' => array_map(function($player) {
                        return [
                            'id' => (int)$player['id'],
                            'name' => $player['name'],
                            'personalSkill' => (int)$player['personalSkill'],
                            'mapReading' => (int)$player['mapReading'],
                            'teamwork' => (int)$player['teamwork'],
                            'reaction' => (int)$player['reaction'],
                            'experience' => (int)$player['experience'],
                            'position' => (int)$player['position'],
                            'totalScore' => (int)$player['totalScore']
                        ];
                    }, $team1),
                    'totalScore' => $team1Score,
                    'playerCount' => count($team1)
                ],
                [
                    'id' => 2,
                    'name' => 'Team 2',
                    'players' => array_map(function($player) {
                        return [
                            'id' => (int)$player['id'],
                            'name' => $player['name'],
                            'personalSkill' => (int)$player['personalSkill'],
                            'mapReading' => (int)$player['mapReading'],
                            'teamwork' => (int)$player['teamwork'],
                            'reaction' => (int)$player['reaction'],
                            'experience' => (int)$player['experience'],
                            'position' => (int)$player['position'],
                            'totalScore' => (int)$player['totalScore']
                        ];
                    }, $team2),
                    'totalScore' => $team2Score,
                    'playerCount' => count($team2)
                ]
            ];

            // Calculate balance
            $balance = abs($team1Score - $team2Score);
            $isBalanced = $balance <= 10; // More lenient for random teams

            echo json_encode([
                'success' => true,
                'message' => 'Random teams created successfully',
                'data' => [
                    'teams' => $teams,
                    'balance' => $balance,
                    'isBalanced' => $isBalanced,
                    'algorithm' => 'random',
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create random teams',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy thông tin teams của match
     */
    public function getTeams($matchId) {
        try {
            // Check if match exists
            $match = $this->db->fetchOne(
                "SELECT * FROM matches WHERE id = :match_id",
                ['match_id' => $matchId]
            );

            if (!$match) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Match not found'
                ]);
                return;
            }

            // Get players in match
            $players = $this->db->fetchAll(
                "SELECT * FROM players WHERE match_id = :match_id ORDER BY totalScore DESC",
                ['match_id' => $matchId]
            );

            if (count($players) < 2) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Not enough players to form teams',
                    'data' => [
                        'teams' => [],
                        'playerCount' => count($players)
                    ]
                ]);
                return;
            }

            // Create teams based on current player order
            $team1 = array_slice($players, 0, ceil(count($players) / 2));
            $team2 = array_slice($players, ceil(count($players) / 2));

            $teams = [
                [
                    'id' => 1,
                    'name' => 'Team 1',
                    'players' => array_map(function($player) {
                        return [
                            'id' => (int)$player['id'],
                            'name' => $player['name'],
                            'totalScore' => (int)$player['totalScore']
                        ];
                    }, $team1),
                    'totalScore' => array_sum(array_column($team1, 'totalScore')),
                    'playerCount' => count($team1)
                ],
                [
                    'id' => 2,
                    'name' => 'Team 2',
                    'players' => array_map(function($player) {
                        return [
                            'id' => (int)$player['id'],
                            'name' => $player['name'],
                            'totalScore' => (int)$player['totalScore']
                        ];
                    }, $team2),
                    'totalScore' => array_sum(array_column($team2, 'totalScore')),
                    'playerCount' => count($team2)
                ]
            ];

            echo json_encode([
                'success' => true,
                'data' => [
                    'teams' => $teams,
                    'match_id' => (int)$matchId,
                    'match_name' => $match['match_name']
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get teams',
                'error' => $e->getMessage()
            ]);
        }
    }
}
?>
