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

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=carnets');
    exit();
}

$id = (int)$_GET['id'];

// Récupérer les détails du carnet
$stmt = $conn->prepare("SELECT * FROM carnets WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$carnet = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si le carnet existe
if (!$carnet) {
    $_SESSION['flash_message'] = "Le carnet de voyage demandé n'existe pas.";
    $_SESSION['flash_type'] = 'error';
    header('Location: admin.php?section=carnets');
    exit();
}

// Récupérer les lieux du carnet
$stmt = $conn->prepare("SELECT location FROM carnet_locations WHERE carnet_id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Récupérer les images du carnet
$stmt = $conn->prepare("SELECT image_path FROM carnet_images WHERE carnet_id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Enregistrer l'activité de l'admin
logAdminActivity('view_carnet', 'Visualisation du carnet ID: ' . $id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisation du Carnet - <?php echo htmlspecialchars($carnet['title']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styleadmin.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .preview-container {
            max-width: 1200px;
            margin: 2rem auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .preview-header {
            background-color: #2c3e50;
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .preview-header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        
        .preview-header .actions a {
            margin-left: 0.5rem;
        }
        
        .preview-content {
            padding: 2rem;
        }
        
        .preview-section {
            margin-bottom: 2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 2rem;
        }
        
        .preview-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .preview-section h2 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .preview-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .preview-meta-item {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .preview-meta-item i {
            margin-right: 0.5rem;
            color: #3498db;
        }
        
        .locations-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .location-badge {
            background-color: #3498db;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
        }
        
        .location-badge i {
            margin-right: 0.35rem;
            font-size: 0.8rem;
        }
        
        .images-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .gallery-item {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        
        .gallery-item-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .gallery-item-overlay {
            opacity: 1;
        }
        
        .gallery-item-overlay a {
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.25rem;
            transition: background-color 0.3s ease;
        }
        
        .gallery-item-overlay a:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
        
        .no-items {
            background-color: #f8f9fa;
            padding: 2rem;
            text-align: center;
            border-radius: 8px;
            color: #6c757d;
        }
        
        .no-items i {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h1><i class="fas fa-book"></i> <?php echo htmlspecialchars($carnet['title']); ?></h1>
            <div class="actions">
                <a href="admin.php?section=carnets&action=edit&id=<?php echo $carnet['id']; ?>" class="btn btn-info">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </a>
               
            </div>
        </div>
        
        <div class="preview-content">
            <div class="preview-section">
                <h2>Informations générales</h2>
                <div class="preview-meta">
                    <div class="preview-meta-item">
                        <i class="fas fa-user"></i>
                        <span>Auteur: <?php echo htmlspecialchars($carnet['author']); ?></span>
                    </div>
                    <div class="preview-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Créé le: <?php echo date('d/m/Y à H:i', strtotime($carnet['created_at'])); ?></span>
                    </div>
                    <div class="preview-meta-item">
                        <i class="fas fa-images"></i>
                        <span><?php echo count($images); ?> image(s)</span>
                    </div>
                    <div class="preview-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo count($locations); ?> lieu(x)</span>
                    </div>
                </div>
            </div>
            
            <div class="preview-section">
                <h2>Lieux visités</h2>
                <?php if (count($locations) > 0): ?>
                    <div class="locations-list">
                        <?php foreach ($locations as $location): ?>
                            <span class="location-badge">
                                <i class="fas fa-map-pin"></i>
                                <?php echo htmlspecialchars($location); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-items">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Aucun lieu n'a été ajouté à ce carnet de voyage.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="preview-section">
                <h2>Galerie d'images</h2>
                <?php if (count($images) > 0): ?>
                    <div class="images-gallery">
                        <?php foreach ($images as $image): ?>
                            <div class="gallery-item">
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Image du carnet">
                                <div class="gallery-item-overlay">
                                    <a href="<?php echo htmlspecialchars($image); ?>" target="_blank" title="Voir l'image">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-items">
                        <i class="fas fa-images"></i>
                        <p>Aucune image n'a été ajoutée à ce carnet de voyage.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmation de suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce carnet de voyage ? Cette action est irréversible et supprimera également toutes les images et lieux associés.</p>
                    <p><strong>Titre:</strong> <?php echo htmlspecialchars($carnet['title']); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <?php
                    // Générer un token CSRF s'il n'existe pas déjà
                    if (!isset($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    }
                    ?>
                    <a href="delete_carnet.php?id=<?php echo $carnet['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" class="btn btn-danger">Confirmer la suppression</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>