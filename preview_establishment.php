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
    header('Location: admin.php?section=establishments');
    exit();
}

$id = (int)$_GET['id'];

// Récupérer les détails de l'établissement
$stmt = $conn->prepare("SELECT * FROM establishments WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$establishment = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'établissement existe
if (!$establishment) {
    $_SESSION['flash_message'] = "L'établissement demandé n'existe pas.";
    $_SESSION['flash_type'] = 'error';
    header('Location: admin.php?section=establishments');
    exit();
}

// Récupérer les informations du propriétaire
$owner_id = $establishment['owner_id'];
$owner_name = getUserName($owner_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisation - <?= htmlspecialchars($establishment['name']) ?></title>
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
        
        .preview-details {
            margin-bottom: 30px;
        }
        
        .preview-details dl {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
        }
        
        .preview-details dt {
            font-weight: bold;
            color: #555;
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
        
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-pending {
            background-color: #ffc107;
            color: black;
        }
        
        .badge-approved {
            background-color: #28a745;
            color: white;
        }
        
        .badge-rejected {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h1 class="preview-title"><?= htmlspecialchars($establishment['name']) ?></h1>
            <span class="badge badge-<?= $establishment['approval_status'] ?>">
                <?php 
                switch($establishment['approval_status']) {
                    case 'pending': echo 'En attente'; break;
                    case 'approved': echo 'Approuvé'; break;
                    case 'rejected': echo 'Rejeté'; break;
                    default: echo $establishment['approval_status']; 
                }
                ?>
            </span>
        </div>
        
        <div class="preview-meta">
            <div><i class="fas fa-user"></i> Propriétaire: <?= htmlspecialchars($owner_name) ?></div>
            <div><i class="fas fa-calendar"></i> Créé le: <?= date('d/m/Y', strtotime($establishment['created_at'])) ?></div>
        </div>
        
        <div class="preview-details">
            <dl>
                <dt>Type:</dt>
                <dd><?= htmlspecialchars($establishment['type']) ?></dd>
                
                <dt>Statut:</dt>
                <dd>
                    <span class="badge badge-<?= $establishment['approval_status'] ?>">
                        <?php 
                        switch($establishment['approval_status']) {
                            case 'pending': echo 'En attente'; break;
                            case 'approved': echo 'Approuvé'; break;
                            case 'rejected': echo 'Rejeté'; break;
                            default: echo $establishment['approval_status']; 
                        }
                        ?>
                    </span>
                </dd>
            </dl>
        </div>
        
        <div class="preview-actions">
            <a href="admin.php?section=establishments" class="btn btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
            
            <?php if ($establishment['approval_status'] == 'pending'): ?>
            <button class="btn btn-approve" data-id="<?= $establishment['id'] ?>" data-table="establishments" onclick="approveItem(this)"><i class="fas fa-check"></i> Approuver</button>
            <button class="btn btn-reject" data-id="<?= $establishment['id'] ?>" data-table="establishments" onclick="rejectItem(this)"><i class="fas fa-times"></i> Rejeter</button>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function approveItem(button) {
            if (confirm('Êtes-vous sûr de vouloir approuver cet établissement ?')) {
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
                        window.location.href = 'admin.php?section=establishments';
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
            if (confirm('Êtes-vous sûr de vouloir rejeter cet établissement ?')) {
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
                        window.location.href = 'admin.php?section=establishments';
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