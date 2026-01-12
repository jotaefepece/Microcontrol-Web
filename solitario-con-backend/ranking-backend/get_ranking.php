<?php
require __DIR__ . "/config/database.php";
header("Content-Type: application/json");

$stmt = $pdo->query("
    SELECT nick, score, created_at
    FROM scores
    ORDER BY score ASC, created_at ASC
    LIMIT 27
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
