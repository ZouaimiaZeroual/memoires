<?php
require_once('db_connect.php');

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid carnet ID']);
    exit;
}

$carnet_id = (int)$_GET['id'];

try {
    // Get carnet details
    $stmt = $pdo->prepare("SELECT * FROM carnets WHERE id = ?");
    $stmt->execute([$carnet_id]);
    $carnet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carnet) {
        echo json_encode(['success' => false, 'message' => 'Carnet not found']);
        exit;
    }

    // Prepare response with the actual database structure
    $response = [
        'success' => true,
        'carnet' => [
            'id' => $carnet['id'],
            'title' => $carnet['title'],
            'author' => $carnet['author'],
            'created_at' => $carnet['created_at'],
            'location' => $carnet['location'],
            'place' => $carnet['place'],
            'transport' => $carnet['transport'],
            'content' => $carnet['content']
        ]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} 