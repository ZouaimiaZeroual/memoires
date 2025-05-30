<?php
// Verify admin access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../admin.php');
    exit();
}

// Process admin password change
$passwordChanged = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validate inputs
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "Tous les champs sont requis";
        } elseif ($new_password !== $confirm_password) {
            $error = "Les nouveaux mots de passe ne correspondent pas";
        } elseif (strlen($new_password) < 8) {
            $error = "Le nouveau mot de passe doit contenir au moins 8 caractères";
        } else {
            // Check if admin table exists, if not create it
            $stmt = $conn->query("SHOW TABLES LIKE 'admins'");
            if ($stmt->rowCount() === 0) {
                // Create admins table
                $conn->exec("CREATE TABLE IF NOT EXISTS admins (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )");
                
                // Insert default admin from session
                $defaultUsername = $_SESSION['username'];
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
                $stmt->execute([$defaultUsername, $hashedPassword, $defaultUsername]);
                
                // Log activity
                logAdminActivity('create_admin_table', 'Created admin table and set up initial admin account');
                
                $passwordChanged = true;
            } else {
                // Check current password against stored password
                $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
                $stmt->execute([$_SESSION['username']]);
                $admin = $stmt->fetch();
                
                if ($admin) {
                    // If using password_hash
                    if (password_verify($current_password, $admin['password'])) {
                        // Update password
                        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
                        $stmt->execute([$hashedPassword, $_SESSION['username']]);
                        
                        // Log activity
                        logAdminActivity('change_password', 'Admin password changed');
                        
                        $passwordChanged = true;
                    } else {
                        $error = "Le mot de passe actuel est incorrect";
                    }
                } else {
                    // Admin not found in database, create new entry
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
                    $stmt->execute([$_SESSION['username'], $hashedPassword, $_SESSION['username']]);
                    
                    // Log activity
                    logAdminActivity('create_admin_account', 'Created new admin account');
                    
                    $passwordChanged = true;
                }
            }
        }
    } elseif ($_POST['action'] === 'update_site_settings') {
        // Process site settings update
        $site_name = $_POST['site_name'] ?? '';
        $site_description = $_POST['site_description'] ?? '';
        $contact_email = $_POST['contact_email'] ?? '';
        
        // Create settings table if it doesn't exist
        $stmt = $conn->query("SHOW TABLES LIKE 'site_settings'");
        if ($stmt->rowCount() === 0) {
            $conn->exec("CREATE TABLE IF NOT EXISTS site_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(255) NOT NULL UNIQUE,
                setting_value TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
        }
        
        // Update or insert settings
        $settings = [
            'site_name' => $site_name,
            'site_description' => $site_description,
            'contact_email' => $contact_email
        ];
        
        foreach ($settings as $key => $value) {
            // Check if setting exists
            $stmt = $conn->prepare("SELECT * FROM site_settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            
            if ($stmt->rowCount() > 0) {
                // Update existing setting
                $stmt = $conn->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            } else {
                // Insert new setting
                $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
                $stmt->execute([$key, $value]);
            }
        }
        
        // Log activity
        logAdminActivity('update_site_settings', 'Updated site settings');
        
        $_SESSION['flash_message'] = "Paramètres du site mis à jour avec succès";
        $_SESSION['flash_type'] = 'success';
        header('Location: admin.php?section=settings');
        exit();
    }
}

// Get current site settings
$site_settings = [];
$stmt = $conn->query("SHOW TABLES LIKE 'site_settings'");
if ($stmt->rowCount() > 0) {
    $stmt = $conn->query("SELECT * FROM site_settings");
    while ($row = $stmt->fetch()) {
        $site_settings[$row['setting_key']] = $row['setting_value'];
    }
}
?>

<div class="page-header">
    <h1>Paramètres</h1>
</div>

<div class="row" style="display: flex; flex-wrap: wrap; margin: -10px;">
    <div class="col" style="flex: 1; min-width: 300px; padding: 10px;">
        <!-- Admin Password Change -->
        <div class="card">
            <div class="card-header">
                <h2>Changer le mot de passe administrateur</h2>
            </div>
            <div class="card-body">
                <?php if ($passwordChanged): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Mot de passe modifié avec succès!
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                        <small class="form-text text-muted">Le mot de passe doit contenir au moins 8 caractères.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col" style="flex: 1; min-width: 300px; padding: 10px;">
        <!-- Site Settings -->
        <div class="card">
            <div class="card-header">
                <h2>Paramètres du site</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <input type="hidden" name="action" value="update_site_settings">
                    
                    <div class="form-group">
                        <label for="site_name">Nom du site</label>
                        <input type="text" id="site_name" name="site_name" class="form-control" value="<?php echo htmlspecialchars($site_settings['site_name'] ?? 'Discover DZ'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description">Description du site</label>
                        <textarea id="site_description" name="site_description" class="form-control" rows="3"><?php echo htmlspecialchars($site_settings['site_description'] ?? 'Une plateforme pour raconter et partager ses expériences!'); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">Email de contact</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($site_settings['contact_email'] ?? ''); ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les paramètres
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

