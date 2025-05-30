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
    header('Location: admin.php?section=experiences');
    exit();
}

$id = (int)$_GET['id'];

// Récupérer les détails de l'expérience
$stmt = $conn->prepare("SELECT * FROM experiences WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$experience = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'expérience existe
if (!$experience) {
    $_SESSION['flash_message'] = "L'expérience demandée n'existe pas.";
    $_SESSION['flash_type'] = 'error';
    header('Location: admin.php?section=experiences');
    exit();
}

// Récupérer les informations de l'utilisateur
$user_id = $experience['user_id'];
$username = getUserName($user_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisation - <?= htmlspecialchars($experience['title']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styleadmin.css">
    <style>
        .preview-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .preview-title {
            font-size: 1.8rem;
            margin: 0;
        }
        
        .preview-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .preview-meta div {
            display: flex;
            align-items: center;
        }
        
        .preview-meta i {
            margin-right: 5px;
        }
        
        .preview-content {
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .preview-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h1 class="preview-title"><?= htmlspecialchars($experience['title']) ?></h1>
            <span class="badge badge-<?= $experience['status'] ?>">
                <?php 
                switch($experience['status']) {
                    case 'draft': echo 'Brouillon'; break;
                    case 'pending': echo 'En attente'; break;
                    case 'published': echo 'Publié'; break;
                    case 'rejected': echo 'Rejeté'; break;
                    default: echo $experience['status']; 
                }
                ?>
            </span>
        </div>
        
        <div class="preview-meta">
            <div><i class="fas fa-user"></i> <?= htmlspecialchars($username) ?></div>
            <div><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($experience['created_at'])) ?></div>
        </div>
        
        <?php if (isset($experience['cover_media_id']) && $experience['cover_media_id']): ?>
        <div class="preview-image">
            <img src="get_media.php?id=<?= $experience['cover_media_id'] ?>" alt="Image de couverture" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 5px;">
        </div>
        <?php endif; ?>
        
        <div class="preview-content">
            <?= nl2br(htmlspecialchars($experience['content'])) ?>
        </div>
        
        <div class="preview-actions">
            <a href="admin.php?section=experiences" class="btn btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
            
            <?php if ($experience['status'] == 'pending'): ?>
            <button class="btn btn-approve" data-id="<?= $experience['id'] ?>" data-table="experiences" onclick="approveItem(this)"><i class="fas fa-check"></i> Approuver</button>
            <button class="btn btn-reject" data-id="<?= $experience['id'] ?>" data-table="experiences" onclick="rejectItem(this)"><i class="fas fa-times"></i> Rejeter</button>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function approveItem(button) {
            if (confirm('Êtes-vous sûr de vouloir approuver cette expérience ?')) {
                const id = button.dataset.id;
                const table = button.dataset.table;
                
                const formData = new FormData();
                formData.append('action', 'approve');
                formData.append('table', table);
                formData.append('id', id);
                
                fetch('admin_handlers.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = 'admin.php?section=experiences';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Une erreur est survenue');
                    console.error('Error:', error);
                });
            }
        }
        
        function rejectItem(button) {
            if (confirm('Êtes-vous sûr de vouloir rejeter cette expérience ?')) {
                const id = button.dataset.id;
                const table = button.dataset.table;
                
                const formData = new FormData();
                formData.append('action', 'reject');
                formData.append('table', table);
                formData.append('id', id);
                
                fetch('admin_handlers.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = 'admin.php?section=experiences';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Une erreur est survenue');
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html>