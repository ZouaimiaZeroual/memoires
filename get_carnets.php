<?php
require_once('db_connect.php');
session_start();

header('Content-Type: application/json');

$author = isset($_SESSION['selectedAuthor']) ? $_SESSION['selectedAuthor'] : null;

if ($author) {
    try {
        // Fetch author's data
        $query = "SELECT 
                    c.id, 
                    c.title, 
                    c.author, 
                    c.created_at,
                    c.location,
                    c.place,
                    c.transport,
                    c.content,
                    GROUP_CONCAT(DISTINCT ci.image_path) AS images,
                    COUNT(DISTINCT c.id) as total_carnets
                FROM carnets c
                LEFT JOIN carnet_images ci ON c.id = ci.carnet_id
                WHERE c.author = ?
                GROUP BY c.id
                ORDER BY c.created_at DESC";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$author]);
        $carnets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total stats
        $totalCarnets = count($carnets);
        $totalPhotos = 0;
        foreach ($carnets as $carnet) {
            if ($carnet['images']) {
                $totalPhotos += count(explode(',', $carnet['images']));
            }
        }

        // Get member since date (first carnet)
        $firstCarnetQuery = "SELECT MIN(created_at) as first_carnet FROM carnets WHERE author = ?";
        $stmt = $pdo->prepare($firstCarnetQuery);
        $stmt->execute([$author]);
        $firstCarnet = $stmt->fetch(PDO::FETCH_ASSOC);
        $memberSince = $firstCarnet['first_carnet'];

        // Get last publication date
        $lastCarnetQuery = "SELECT MAX(created_at) as last_carnet FROM carnets WHERE author = ?";
        $stmt = $pdo->prepare($lastCarnetQuery);
        $stmt->execute([$author]);
        $lastCarnet = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastPublication = $lastCarnet['last_carnet'];

        echo json_encode([
            'success' => true,
            'data' => [
                'carnets' => $carnets,
                'stats' => [
                    'totalCarnets' => $totalCarnets,
                    'totalPhotos' => $totalPhotos,
                    'memberSince' => $memberSince,
                    'lastPublication' => $lastPublication
                ]
            ]
        ]);

    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur de chargement des données: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Auteur non spécifié'
    ]);
} 