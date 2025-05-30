<?php
// Assurez-vous que db_connect.php est inclus et que $conn est disponible
if (!isset($conn)) {
    require_once('includes/db_connect.php');
}

// Gérer les actions CRUD pour les administrateurs
$admin_action = $_POST['admin_action'] ?? $_GET['admin_action'] ?? null;
$admin_id = $_POST['admin_id'] ?? $_GET['admin_id'] ?? null;
$admin_username = $_POST['admin_username'] ?? '';
$admin_password = $_POST['admin_password'] ?? '';

$feedback_message = '';
$feedback_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $admin_action) {
    try {
        if ($admin_action === 'add' && !empty($admin_username) && !empty($admin_password)) {
            $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->execute([$admin_username, $hashed_password]);
            logAdminActivity('Ajout Admin', 'Nouvel admin ajouté: ' . $admin_username);
            $feedback_message = 'Administrateur ajouté avec succès.';
            $feedback_type = 'success';
        } elseif ($admin_action === 'edit' && !empty($admin_id) && !empty($admin_username)) {
            if (!empty($admin_password)) {
                $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE admins SET username = ?, password = ? WHERE id = ?");
                $stmt->execute([$admin_username, $hashed_password, $admin_id]);
            } else {
                $stmt = $conn->prepare("UPDATE admins SET username = ? WHERE id = ?");
                $stmt->execute([$admin_username, $admin_id]);
            }
            logAdminActivity('Modification Admin', 'Admin ID ' . $admin_id . ' modifié. Nouveau nom: ' . $admin_username);
            $feedback_message = 'Administrateur mis à jour avec succès.';
            $feedback_type = 'success';
        } elseif ($admin_action === 'delete' && !empty($admin_id)) {
            // Empêcher la suppression du dernier admin ou de l'admin actuellement connecté (si possible à déterminer)
            $stmt_count = $conn->query("SELECT COUNT(*) FROM admins");
            if ($stmt_count->fetchColumn() > 1) {
                // Optionnel: Vérifier si l'admin à supprimer est l'admin actuellement connecté
                // if ($_SESSION['admin_id'] == $admin_id) { 
                //    $feedback_message = 'Vous ne pouvez pas supprimer votre propre compte administrateur.';
                //    $feedback_type = 'error';
                // } else {
                    $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
                    $stmt->execute([$admin_id]);
                    logAdminActivity('Suppression Admin', 'Admin ID ' . $admin_id . ' supprimé.');
                    $feedback_message = 'Administrateur supprimé avec succès.';
                    $feedback_type = 'success';
                // }
            } else {
                $feedback_message = 'Impossible de supprimer le dernier administrateur.';
                $feedback_type = 'error';
            }
        }
    } catch (PDOException $e) {
        $feedback_message = "Erreur lors de l'opération : " . $e->getMessage();
        $feedback_type = 'error';
        logAdminActivity('Erreur Admin CRUD', $e->getMessage());
    }
    // Pour éviter la resoumission du formulaire en cas de rafraîchissement
    if ($feedback_type === 'success') {
        $_SESSION['flash_message'] = $feedback_message;
        $_SESSION['flash_type'] = $feedback_type;
        header("Location: admin.php?section=admins");
        exit();
    }
}

// Récupérer la liste des administrateurs
$stmt_admins = $conn->query("SELECT id, username, created_at FROM admins ORDER BY username ASC");
$admins_list = $stmt_admins->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les données de l'admin à éditer
$editing_admin = null;
if ($admin_action === 'edit_form' && !empty($admin_id)) {
    $stmt_edit = $conn->prepare("SELECT id, username FROM admins WHERE id = ?");
    $stmt_edit->execute([$admin_id]);
    $editing_admin = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}

?>

<div class="page-header">
    <h1>Gérer les Administrateurs</h1>
</div>

<?php if (!empty($feedback_message)): ?>
    <div class="alert alert-<?php echo $feedback_type; ?>">
        <?php echo htmlspecialchars($feedback_message); ?>
    </div>
<?php endif; ?>

<div class="form-container card">
    <div class="card-header">
        <h2><?php echo $editing_admin ? 'Modifier l\'administrateur' : 'Ajouter un nouvel administrateur'; ?></h2>
    </div>
    <div class="card-body">
        <form action="admin.php?section=admins" method="POST">
            <input type="hidden" name="admin_action" value="<?php echo $editing_admin ? 'edit' : 'add'; ?>">
            <?php if ($editing_admin): ?>
                <input type="hidden" name="admin_id" value="<?php echo $editing_admin['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="admin_username">Nom d'utilisateur</label>
                <input type="text" name="admin_username" id="admin_username" class="form-control" 
                       value="<?php echo htmlspecialchars($editing_admin['username'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="admin_password">Mot de passe <?php echo $editing_admin ? '(Laissez vide pour ne pas changer)' : ''; ?></label>
                <input type="password" name="admin_password" id="admin_password" class="form-control" <?php echo !$editing_admin ? 'required' : ''; ?>>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-<?php echo $editing_admin ? 'save' : 'plus'; ?>"></i> <?php echo $editing_admin ? 'Mettre à jour' : 'Ajouter'; ?>
            </button>
            <?php if ($editing_admin): ?>
                <a href="admin.php?section=admins" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler l'édition
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="table-container card">
    <div class="card-header">
        <h2>Liste des Administrateurs</h2>
    </div>
    <div class="card-body table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins_list as $admin_item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($admin_item['id']); ?></td>
                    <td><?php echo htmlspecialchars($admin_item['username']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($admin_item['created_at']))); ?></td>
                    <td>
                        <a href="admin.php?section=admins&admin_action=edit_form&admin_id=<?php echo $admin_item['id']; ?>" class="btn btn-sm btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if (count($admins_list) > 1 && ($_SESSION['admin_id'] ?? null) != $admin_item['id']): // Empêche la suppression du dernier admin et de soi-même ?>
                        <a href="admin.php?section=admins&admin_action=delete&admin_id=<?php echo $admin_item['id']; ?>" 
                           class="btn btn-sm btn-danger" title="Supprimer" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ? Cette action est irréversible.');">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($admins_list)): ?>
                <tr>
                    <td colspan="4" class="text-center">Aucun administrateur trouvé.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>