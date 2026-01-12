<?php
header('Content-Type: application/json');
echo json_encode([
  "debug" => "ROOT submit_score.php",
  "post" => $_POST,
  "raw" => file_get_contents("php://input")
]);
exit;

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// Intentar leer JSON
$input = json_decode(file_get_contents("php://input"), true);

// Si no vino JSON, intentar POST clásico
if (!$input) {
    $input = $_POST;
}

$player = $input['player'] ?? null;
$score  = $input['score']  ?? null;

// Validaciones claras
if (!$player || !is_string($player) || strlen(trim($player)) < 2) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid player name"]);
    exit;
}

if (!is_numeric($score) || $score < 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid score"]);
    exit;
}

require __DIR__ . '/config/database.php';

try {
    $stmt = $pdo->prepare(
        "INSERT INTO ranking (player, score) VALUES (:player, :score)"
    );
    $stmt->execute([
        ':player' => trim($player),
        ':score'  => (int)$score
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to save score"]);
}
