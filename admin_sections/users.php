<?php
// Verify admin access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../admin.php');
    exit();
}

require_once('includes/user_functions.php');

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch users from database with pagination and experience count
$stmt = $conn->prepare("SELECT u.*, COUNT(e.id) as experience_count 
                        FROM users u 
                        LEFT JOIN experiences e ON u.id = e.user_id 
                        GROUP BY u.id 
                        ORDER BY u.created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Count total users for pagination
$stmt = $conn->prepare("SELECT COUNT(*) FROM users");
$stmt->execute();
$totalUsers = $stmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);

// Get user for editing if ID is provided
$editUser = null;
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
    $stmt->execute();
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content-header">
    <h2><i class="fas fa-users"></i> Gestion des Utilisateurs</h2>
    <div class="stats"><?= $totalUsers ?> utilisateurs au total</div>
    <button id="add-user-btn" class="btn primary"><i class="fas fa-plus"></i> Ajouter un utilisateur</button>
</div>

<!-- Formulaire d'ajout/modification d'utilisateur -->
<div id="user-form-container" class="form-container" style="display: <?= $editUser ? 'block' : 'none' ?>">
    <form id="user-form" class="admin-form">
        <input type="hidden" name="action" value="<?= $editUser ? 'edit' : 'add' ?>">
        <input type="hidden" name="table" value="users">
        <?php if ($editUser): ?>
        <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" value="<?= $editUser ? htmlspecialchars($editUser['username']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= $editUser ? htmlspecialchars($editUser['email']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <option value="user" <?= ($editUser && $editUser['role'] == 'user') ? 'selected' : '' ?>>Utilisateur</option>
                <option value="business" <?= ($editUser && $editUser['role'] == 'business') ? 'selected' : '' ?>>Professionnel</option>
                <option value="admin" <?= ($editUser && $editUser['role'] == 'admin') ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn primary"><?= $editUser ? 'Mettre à jour' : 'Ajouter' ?></button>
            <button type="button" id="cancel-user-form" class="btn secondary">Annuler</button>
        </div>
    </form>
</div>

<div class="filter-container">
    <input type="text" class="filter-input" placeholder="Rechercher un utilisateur..." data-filter="users-table">
    <select class="filter-input">
        <option value="">Tous les rôles</option>
        <option value="admin">Administrateurs</option>
        <option value="user">Utilisateurs standard</option>
    </select>
</div>

<table class="moderation-table" id="users-table">
    <thead>
        <tr>
            <th class="sortable">Nom d'utilisateur</th>
            <th class="sortable">Email</th>
            <th class="sortable">Date d'inscription</th>
            <th class="sortable">Rôle</th>
            <th class="sortable">Expériences</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="../image/photo_profil.jpg" alt="<?= htmlspecialchars($user['username']) ?>" 
                         style="width: 32px; height: 32px; border-radius: 50%; margin-right: 10px;">
                    <?= htmlspecialchars($user['username']) ?>
                </div>
            </td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
            <td>
                <?php 
                $roleLabels = [
                    'user' => '<span class="badge badge-user">Utilisateur</span>',
                    'business' => '<span class="badge badge-business">Professionnel</span>',
                    'admin' => '<span class="badge badge-admin">Admin</span>'
                ];
                echo $roleLabels[$user['role'] ?? 'user'];
                ?>
            </td>
            <td><?= $user['experience_count'] ?? 0 ?></td>
            <td class="actions">
                <a href="view_user.php?id=<?= $user['id'] ?>" class="btn preview"><i class="fas fa-eye"></i></a>
                <a href="?section=users&edit_id=<?= $user['id'] ?>" class="btn edit"><i class="fas fa-edit"></i></a>
                <?php if ($user['role'] !== 'admin'): ?>
                <button class="btn delete" data-id="<?= $user['id'] ?>" data-table="users"><i class="fas fa-trash"></i></button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
    <a href="?section=users&page=<?= $page - 1 ?>" class="pagination-item"><i class="fas fa-chevron-left"></i></a>
    <?php else: ?>
    <span class="pagination-item disabled"><i class="fas fa-chevron-left"></i></span>
    <?php endif; ?>
    
    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
    <a href="?section=users&page=<?= $i ?>" class="pagination-item <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    
    <?php if ($page < $totalPages): ?>
    <a href="?section=users&page=<?= $page + 1 ?>" class="pagination-item"><i class="fas fa-chevron-right"></i></a>
    <?php else: ?>
    <span class="pagination-item disabled"><i class="fas fa-chevron-right"></i></span>
    <?php endif; ?>
</div>
<?php endif; ?>

<style>
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .badge-admin {
        background-color: var(--secondary-color);
        color: white;
    }
    
    .badge-business {
        background-color: #17a2b8;
        color: white;
    }
    
    .badge-user {
        background-color: var(--light-color);
        color: var(--dark-color);
    }
</style>

<script>
    // Ajouter des gestionnaires d'événements pour les boutons d'édition
    document.querySelectorAll('.btn.edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            // Simuler l'ouverture d'un modal d'édition
            alert(`Édition de l'utilisateur ID: ${userId}`);
        });
    });
</script>