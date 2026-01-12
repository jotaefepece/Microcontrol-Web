<?php
require __DIR__ . '/config/database.php';

try {
    // Vaciar ranking
    $pdo->exec("DELETE FROM scores");

    // Insertar 27 registros dummy
    $stmt = $pdo->prepare("
        INSERT INTO scores (nick, score, created_at)
        SELECT 'empty', 99999, NOW()
        FROM generate_series(1, 27)
    ");
    $stmt->execute();

    echo json_encode([
        "status" => "ok",
        "message" => "Ranking reiniciado"
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
