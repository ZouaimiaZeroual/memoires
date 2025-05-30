<?php
// Verify admin access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../admin.php');
    exit();
}

require_once(__DIR__ . '/../includes/user_functions.php');

// Fetch all experiences from database
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$where_clause = $status_filter !== 'all' ? "WHERE status = :status" : "";

$stmt = $conn->prepare("SELECT * FROM experiences $where_clause ORDER BY created_at DESC");
if ($status_filter !== 'all') {
    $stmt->bindParam(':status', $status_filter);
}
$stmt->execute();
$experiences = $stmt->fetchAll();

// Get experience for editing if ID is provided
$editExperience = null;
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM experiences WHERE id = :id");
    $stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
    $stmt->execute();
    $editExperience = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content-header">
    <h2><i class="fas fa-check-circle"></i> Gestion des Expériences</h2>
    <div class="stats"><?= count($experiences) ?> expériences</div>
    <button id="add-experience-btn" class="btn primary"><i class="fas fa-plus"></i> Ajouter une expérience</button>
</div>

<!-- Formulaire d'ajout/modification d'expérience -->
<div id="experience-form-container" class="form-container" style="display: <?= $editExperience ? 'block' : 'none' ?>">
    <form id="experience-form" class="admin-form">
        <input type="hidden" name="action" value="<?= $editExperience ? 'edit' : 'add' ?>">
        <input type="hidden" name="table" value="experiences">
        <?php if ($editExperience): ?>
        <input type="hidden" name="id" value="<?= $editExperience['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="<?= $editExperience ? htmlspecialchars($editExperience['title']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="content">Contenu</label>
            <textarea id="content" name="content" rows="5" required><?= $editExperience ? htmlspecialchars($editExperience['content']) : '' ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="user_id">ID Utilisateur</label>
            <input type="number" id="user_id" name="user_id" value="<?= $editExperience ? htmlspecialchars($editExperience['user_id']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="location_id">Lieu (ID Location)</label>
            <select id="location_id" name="location_id">
                <option value="">-- Sélectionner un lieu --</option>
                <?php
                // Récupérer toutes les locations
                $locStmt = $conn->prepare("SELECT id, name, city FROM locations ORDER BY name");
                $locStmt->execute();
                $locations = $locStmt->fetchAll();
                
                foreach ($locations as $location):
                    $selected = ($editExperience && $editExperience['location_id'] == $location['id']) ? 'selected' : '';
                    echo "<option value=\"{$location['id']}\" {$selected}>{$location['name']} ({$location['city']})</option>";
                endforeach;
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="cover_media_id">Image de couverture (ID Media)</label>
            <input type="number" id="cover_media_id" name="cover_media_id" value="<?= $editExperience ? htmlspecialchars($editExperience['cover_media_id']) : '' ?>">
            <?php if ($editExperience && $editExperience['cover_media_id']): 
                $mediaStmt = $conn->prepare("SELECT file_path FROM media WHERE id = :id");
                $mediaStmt->bindParam(':id', $editExperience['cover_media_id']);
                $mediaStmt->execute();
                $media = $mediaStmt->fetch();
                if ($media): ?>
                <div class="media-preview">
                    <img src="<?= htmlspecialchars($media['file_path']) ?>" alt="Image de couverture" style="max-width: 200px; margin-top: 10px;">
                </div>
            <?php endif; endif; ?>
        </div>
        
        <div class="form-group">
            <label>Catégories</label>
            <div class="categories-container" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                <?php
                // Récupérer toutes les catégories
                $catStmt = $conn->prepare("SELECT id, name FROM categories ORDER BY name");
                $catStmt->execute();
                $categories = $catStmt->fetchAll();
                
                // Si en mode édition, récupérer les catégories de l'expérience
                $selectedCategories = [];
                if ($editExperience) {
                    $selCatStmt = $conn->prepare("SELECT category_id FROM experience_categories WHERE experience_id = :experience_id");
                    $selCatStmt->bindParam(':experience_id', $editExperience['id']);
                    $selCatStmt->execute();
                    $selectedCategories = $selCatStmt->fetchAll(PDO::FETCH_COLUMN);
                }
                
                foreach ($categories as $category):
                    $checked = in_array($category['id'], $selectedCategories) ? 'checked' : '';
                ?>
                <div class="category-checkbox" style="background-color: #f0f0f0; padding: 5px 10px; border-radius: 4px;">
                    <input type="checkbox" id="category_<?= $category['id'] ?>" name="categories[]" value="<?= $category['id'] ?>" <?= $checked ?>>
                    <label for="category_<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="draft" <?= ($editExperience && $editExperience['status'] == 'draft') ? 'selected' : '' ?>>Brouillon</option>
                <option value="pending" <?= ($editExperience && $editExperience['status'] == 'pending') ? 'selected' : '' ?>>En attente</option>
                <option value="published" <?= ($editExperience && $editExperience['status'] == 'published') ? 'selected' : '' ?>>Publié</option>
                <option value="rejected" <?= ($editExperience && $editExperience['status'] == 'rejected') ? 'selected' : '' ?>>Rejeté</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn primary"><?= $editExperience ? 'Mettre à jour' : 'Ajouter' ?></button>
            <button type="button" id="cancel-experience-form" class="btn secondary">Annuler</button>
        </div>
    </form>
</div>

<div class="filter-container">
    <select class="filter-input" id="status-filter" onchange="window.location.href='?section=experiences&status='+this.value">
        <option value="all" <?= $status_filter == 'all' ? 'selected' : '' ?>>Tous les statuts</option>
        <option value="draft" <?= $status_filter == 'draft' ? 'selected' : '' ?>>Brouillons</option>
        <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>En attente</option>
        <option value="published" <?= $status_filter == 'published' ? 'selected' : '' ?>>Publiés</option>
        <option value="rejected" <?= $status_filter == 'rejected' ? 'selected' : '' ?>>Rejetés</option>
    </select>
    <input type="text" class="filter-input" placeholder="Rechercher une expérience..." data-filter="experiences-table">
</div>

<table class="moderation-table" id="experiences-table">
    <thead>
        <tr>
            <th class="sortable">Titre</th>
            <th class="sortable">Auteur</th>
            <th class="sortable">Statut</th>
            <th class="sortable">Date</th>           
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($experiences as $exp): ?>
        <tr>
            <td><?= htmlspecialchars($exp['title']) ?></td>
            <td><?= getUserName($exp['user_id']) ?></td>
            <td>
                <span class="badge badge-<?= $exp['status'] ?>">
                    <?php 
                    switch($exp['status']) {
                        case 'draft': echo 'Brouillon'; break;
                        case 'pending': echo 'En attente'; break;
                        case 'published': echo 'Publié'; break;
                        case 'rejected': echo 'Rejeté'; break;
                        default: echo $exp['status']; 
                    }
                    ?>
                </span>
            </td>
            <td><?= date('d/m/Y', strtotime($exp['created_at'])) ?></td>
            <td class="actions">
                <a href="preview_experience.php?id=<?= $exp['id'] ?>" class="btn preview"><i class="fas fa-eye"></i></a>
                <a href="?section=experiences&edit_id=<?= $exp['id'] ?>" class="btn edit"><i class="fas fa-edit"></i></a>
                <?php if ($exp['status'] == 'pending'): ?>
                <button class="btn approve" data-id="<?= $exp['id'] ?>" data-table="experiences"><i class="fas fa-check"></i></button>
                <button class="btn reject" data-id="<?= $exp['id'] ?>" data-table="experiences"><i class="fas fa-times"></i></button>
                <?php endif; ?>
                <button class="btn delete" data-id="<?= $exp['id'] ?>" data-table="experiences"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<style>
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .badge-draft {
        background-color: #6c757d;
        color: white;
    }
    
    .badge-pending {
        background-color: #ffc107;
        color: black;
    }
    
    .badge-published {
        background-color: #28a745;
        color: white;
    }
    
    .badge-rejected {
        background-color: #dc3545;
        color: white;
    }
    
    .form-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
</style>

<script>
    // Bouton pour afficher le formulaire d'ajout
    document.getElementById('add-experience-btn').addEventListener('click', function() {
        const formContainer = document.getElementById('experience-form-container');
        formContainer.style.display = 'block';
        // Réinitialiser le formulaire
        document.getElementById('experience-form').reset();
        // Changer l'action à 'add'
        document.querySelector('#experience-form input[name="action"]').value = 'add';
    });
    
    // Bouton pour annuler le formulaire
    document.getElementById('cancel-experience-form').addEventListener('click', function() {
        const formContainer = document.getElementById('experience-form-container');
        formContainer.style.display = 'none';
        // Rediriger vers la page sans paramètre edit_id
        if (window.location.href.includes('edit_id')) {
            window.location.href = window.location.pathname + '?section=experiences';
        }
    });
</script>