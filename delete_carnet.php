<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Connexion à la base de données
require_once('includes/db_connect.php');
require_once('includes/user_functions.php');

// Vérifier si l'ID est fourni et si le token CSRF est valide
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
    $_SESSION['flash_message'] = "Action non autorisée.";
    $_SESSION['flash_type'] = 'error';
    header('Location: admin.php?section=carnets');
    exit();
}

$id = (int)$_GET['id'];

// Vérifier si le carnet existe
$stmt = $conn->prepare("SELECT id FROM carnets WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    $_SESSION['flash_message'] = "Le carnet de voyage demandé n'existe pas.";
    $_SESSION['flash_type'] = 'error';
    header('Location: admin.php?section=carnets');
    exit();
}

try {
    // Commencer une transaction
    $conn->beginTransaction();
    
    // Supprimer d'abord les enregistrements liés dans les tables associées
    // Supprimer les lieux
    $stmt = $conn->prepare("DELETE FROM carnet_locations WHERE carnet_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Récupérer les chemins des images pour pouvoir les supprimer du serveur
    $stmt = $conn->prepare("SELECT image_path FROM carnet_images WHERE carnet_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Supprimer les enregistrements d'images de la base de données
    $stmt = $conn->prepare("DELETE FROM carnet_images WHERE carnet_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Supprimer le carnet lui-même
    $stmt = $conn->prepare("DELETE FROM carnets WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Valider la transaction
    $conn->commit();
    
    // Supprimer les fichiers d'images du serveur
    foreach ($images as $image_path) {
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Enregistrer l'activité de l'admin
    logAdminActivity('delete_carnet', 'Suppression du carnet ID: ' . $id);
    
    $_SESSION['flash_message'] = "Le carnet de voyage a été supprimé avec succès.";
    $_SESSION['flash_type'] = 'success';
} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction
    $conn->rollBack();
    
    $_SESSION['flash_message'] = "Une erreur est survenue lors de la suppression du carnet: " . $e->getMessage();
    $_SESSION['flash_type'] = 'error';
}

// Rediriger vers la liste des carnets
header('Location: admin.php?section=carnets');
exit();