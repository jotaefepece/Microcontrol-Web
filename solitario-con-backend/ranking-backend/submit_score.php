<?php
require __DIR__ . "/config/database.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$nick  = trim($data['nick'] ?? '');
$score = $data['score'] ?? null;

if ($nick === '' || strlen($nick) > 15 || !is_numeric($score) || $score < 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO scores (nick, score) VALUES (:nick, :score)"
);
$stmt->execute([
    ':nick'  => $nick,
    ':score' => (int)$score
]);

$pdo->exec("
    DELETE FROM scores
    WHERE id NOT IN (
        SELECT id FROM scores
        ORDER BY score ASC, created_at ASC
        LIMIT 27
    )
");

echo json_encode(["status" => "saved"]);
