<?php
require_once('db_connect.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID manquant']);
        exit;
    }

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Delete associated images first
        $query = "DELETE FROM carnet_images WHERE carnet_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $data['id']]);

        // Delete the carnet
        $query = "DELETE FROM carnets WHERE id = :id AND author = :author";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([
            ':id' => $data['id'],
            ':author' => $_SESSION['selectedAuthor']
        ]);

        if ($result) {
            $pdo->commit();
            echo json_encode(['success' => true]);
        } else {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    } catch(PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
} 