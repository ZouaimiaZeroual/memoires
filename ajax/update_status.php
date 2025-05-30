<?php
session_start();
require_once('../includes/db_connect.php');

// Verify admin access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit();
}

// Check if required parameters are present
if (!isset($_POST['id']) || !isset($_POST['status']) || !isset($_POST['table'])) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit();
}

$id = (int)$_POST['id'];
$status = $_POST['status'];
$table = $_POST['table'];

// Validate status
$allowed_statuses = ['pending', 'approved', 'rejected'];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Statut invalide']);
    exit();
}

try {
    // Update the status in the database
    $stmt = $conn->prepare("UPDATE $table SET approval_status = :status WHERE id = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucun enregistrement mis à jour']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?> 