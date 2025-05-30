<?php
session_start();
require_once('../includes/db_connect.php');

// Verify admin access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit();
}

// Check if required parameters are present
if (!isset($_POST['id']) || !isset($_POST['table'])) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit();
}

$id = (int)$_POST['id'];
$table = $_POST['table'];

// Validate table name to prevent SQL injection
$allowed_tables = ['establishments', 'users', 'locations', 'categories'];
if (!in_array($table, $allowed_tables)) {
    echo json_encode(['success' => false, 'message' => 'Table invalide']);
    exit();
}

try {
    // Begin transaction
    $conn->beginTransaction();

    // Delete related records first (if any)
    switch ($table) {
        case 'establishments':
            // Delete related establishment_categories records
            $stmt = $conn->prepare("DELETE FROM establishment_categories WHERE establishment_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            break;
    }

    // Delete the main record
    $stmt = $conn->prepare("DELETE FROM $table WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true]);
    } else {
        // Rollback transaction
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Aucun enregistrement supprimé']);
    }
} catch (PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?> 