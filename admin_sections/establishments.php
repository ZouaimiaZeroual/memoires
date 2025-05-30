<?php
// Verify admin access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../admin.php');
    exit();
}

require_once(__DIR__ . '/../includes/user_functions.php');

// Fetch all establishments from database
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$where_clause = $status_filter !== 'all' ? "WHERE approval_status = :status" : "";

$stmt = $conn->prepare("SELECT * FROM establishments $where_clause ORDER BY created_at DESC");
if ($status_filter !== 'all') {
    $stmt->bindParam(':status', $status_filter);
}
$stmt->execute();
$establishments = $stmt->fetchAll();

// Get establishment for editing if ID is provided
$editEstablishment = null;
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM establishments WHERE id = :id");
    $stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
    $stmt->execute();
    $editEstablishment = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content-header">
    <h2><i class="fas fa-store"></i> Gestion des Établissements</h2>
    <div class="stats"><?= count($establishments) ?> établissements</div>
    <button id="add-establishment-btn" class="btn primary"><i class="fas fa-plus"></i> Ajouter un établissement</button>
</div>

<!-- Formulaire d'ajout/modification d'établissement -->
<div id="establishment-form-container" class="form-container" style="display: <?= $editEstablishment ? 'block' : 'none' ?>">
    <form id="establishment-form" class="admin-form">
        <input type="hidden" name="action" value="<?= $editEstablishment ? 'edit' : 'add' ?>">
        <input type="hidden" name="table" value="establishments">
        <?php if ($editEstablishment): ?>
        <input type="hidden" name="id" value="<?= $editEstablishment['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="nom_etablissement">Nom de l'établissement</label>
            <input type="text" id="nom_etablissement" name="nom_etablissement" value="<?= $editEstablishment ? htmlspecialchars($editEstablishment['nom_etablissement']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= $editEstablishment ? htmlspecialchars($editEstablishment['nom']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?= $editEstablishment ? htmlspecialchars($editEstablishment['prenom']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" value="<?= $editEstablishment ? htmlspecialchars($editEstablishment['telephone']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= $editEstablishment ? htmlspecialchars($editEstablishment['email']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="type">Type</label>
            <select id="type" name="type" required>
                <option value="Hôtel" <?= ($editEstablishment && $editEstablishment['type'] == 'Hôtel') ? 'selected' : '' ?>>Hôtel</option>
                <option value="Restaurant" <?= ($editEstablishment && $editEstablishment['type'] == 'Restaurant') ? 'selected' : '' ?>>Restaurant</option>
                <option value="Clinique" <?= ($editEstablishment && $editEstablishment['type'] == 'Clinique') ? 'selected' : '' ?>>Clinique</option>
                <option value="Hôpital" <?= ($editEstablishment && $editEstablishment['type'] == 'Hôpital') ? 'selected' : '' ?>>Hôpital</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="wilaya">Wilaya</label>
            <input type="text" id="wilaya" name="wilaya" value="<?= $editEstablishment ? htmlspecialchars($editEstablishment['wilaya']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="approval_status">Statut d'approbation</label>
            <select id="approval_status" name="approval_status">
                <option value="pending" <?= ($editEstablishment && $editEstablishment['approval_status'] == 'pending') ? 'selected' : '' ?>>En attente</option>
                <option value="approved" <?= ($editEstablishment && $editEstablishment['approval_status'] == 'approved') ? 'selected' : '' ?>>Approuvé</option>
                <option value="rejected" <?= ($editEstablishment && $editEstablishment['approval_status'] == 'rejected') ? 'selected' : '' ?>>Rejeté</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn primary"><?= $editEstablishment ? 'Mettre à jour' : 'Ajouter' ?></button>
            <button type="button" id="cancel-establishment-form" class="btn secondary">Annuler</button>
        </div>
    </form>
</div>

<div class="filter-container">
    <select class="filter-input" id="status-filter" onchange="window.location.href='?section=establishments&status='+this.value">
        <option value="all" <?= $status_filter == 'all' ? 'selected' : '' ?>>Tous les statuts</option>
        <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>En attente</option>
        <option value="approved" <?= $status_filter == 'approved' ? 'selected' : '' ?>>Approuvés</option>
        <option value="rejected" <?= $status_filter == 'rejected' ? 'selected' : '' ?>>Rejetés</option>
    </select>
    <input type="text" class="filter-input" placeholder="Rechercher un établissement..." data-filter="establishments-table">
</div>

<table class="moderation-table" id="establishments-table">
    <thead>
        <tr>
            <th class="sortable">ID</th>
            <th class="sortable">Nom Établissement</th>
            <th class="sortable">Nom</th>
            <th class="sortable">Prénom</th>
            <th class="sortable">Téléphone</th>
            <th class="sortable">Email</th>
            <th class="sortable">Type</th>
            <th class="sortable">Wilaya</th>
            <th class="sortable">Statut</th>
            <th class="sortable">Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($establishments as $est): ?>
        <tr>
            <td><?= htmlspecialchars($est['id']) ?></td>
            <td><?= htmlspecialchars($est['nom_etablissement']) ?></td>
            <td><?= htmlspecialchars($est['nom']) ?></td>
            <td><?= htmlspecialchars($est['prenom']) ?></td>
            <td><?= htmlspecialchars($est['telephone']) ?></td>
            <td><?= htmlspecialchars($est['email']) ?></td>
            <td><?= htmlspecialchars($est['type']) ?></td>
            <td><?= htmlspecialchars($est['wilaya']) ?></td>
            <td>
                <span class="badge badge-<?= $est['approval_status'] ?>">
                    <?php 
                    switch($est['approval_status']) {
                        case 'pending': echo 'En attente'; break;
                        case 'approved': echo 'Approuvé'; break;
                        case 'rejected': echo 'Rejeté'; break;
                        default: echo $est['approval_status']; 
                    }
                    ?>
                </span>
            </td>
            <td><?= date('d/m/Y', strtotime($est['created_at'])) ?></td>
            <td class="actions">
                <a href="preview_establishment.php?id=<?= $est['id'] ?>" class="btn preview"><i class="fas fa-eye"></i></a>
                <a href="?section=establishments&edit_id=<?= $est['id'] ?>" class="btn edit"><i class="fas fa-edit"></i></a>
                <?php if ($est['approval_status'] == 'pending'): ?>
            
                <?php endif; ?>
                <button class="btn delete" data-id="<?= $est['id'] ?>" data-table="establishments"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<style>
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

<script>
    // Bouton pour afficher le formulaire d'ajout
    document.getElementById('add-establishment-btn').addEventListener('click', function() {
        const formContainer = document.getElementById('establishment-form-container');
        formContainer.style.display = 'block';
        // Réinitialiser le formulaire
        document.getElementById('establishment-form').reset();
        // Changer l'action à 'add'
        document.querySelector('#establishment-form input[name="action"]').value = 'add';
    });
    
    // Bouton pour annuler le formulaire
    document.getElementById('cancel-establishment-form').addEventListener('click', function() {
        const formContainer = document.getElementById('establishment-form-container');
        formContainer.style.display = 'none';
        // Rediriger vers la page sans paramètre edit_id
        if (window.location.href.includes('edit_id')) {
            window.location.href = window.location.pathname + '?section=establishments';
        }
    });

    // Handle approve and reject buttons
    document.querySelectorAll('.btn.approve, .btn.reject').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const action = this.classList.contains('approve') ? 'approve' : 'reject';
            const status = action === 'approve' ? 'approved' : 'rejected';
            
            if (confirm(`Êtes-vous sûr de vouloir ${action === 'approve' ? 'approuver' : 'rejeter'} cet établissement ?`)) {
                fetch('ajax/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}&status=${status}&table=establishments`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the status badge
                        const row = this.closest('tr');
                        const statusCell = row.querySelector('td:nth-child(9)');
                        statusCell.innerHTML = `
                            <span class="badge badge-${status}">
                                ${status === 'approved' ? 'Approuvé' : 'Rejeté'}
                            </span>
                        `;
                        
                        // Remove approve/reject buttons
                        const actionsCell = row.querySelector('.actions');
                        const approveBtn = actionsCell.querySelector('.approve');
                        const rejectBtn = actionsCell.querySelector('.reject');
                        if (approveBtn) approveBtn.remove();
                        if (rejectBtn) rejectBtn.remove();
                        
                        // Show success message
                        alert(`Établissement ${action === 'approve' ? 'approuvé' : 'rejeté'} avec succès !`);
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la mise à jour du statut.');
                });
            }
        });
    });

    // Handle delete buttons
    document.querySelectorAll('.btn.delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const table = this.dataset.table;
            
            if (confirm('Êtes-vous sûr de vouloir supprimer cet établissement ? Cette action est irréversible.')) {
                fetch('ajax/delete_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}&table=${table}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        const row = this.closest('tr');
                        row.remove();
                        // Show success message
                        alert('Établissement supprimé avec succès !');
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la suppression.');
                });
            }
        });
    });
</script>