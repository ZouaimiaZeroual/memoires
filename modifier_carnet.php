<?php
require_once('db_connect.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['title']) || !isset($data['content'])) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        exit;
    }

    try {
        $query = "UPDATE carnets SET 
                  title = :title,
                  content = :content,
                  location = :location,
                  place = :place,
                  transport = :transport
                  WHERE id = :id AND author = :author";
        
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':location' => $data['location'],
            ':place' => $data['place'],
            ':transport' => $data['transport'],
            ':author' => $_SESSION['selectedAuthor']
        ]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
} 