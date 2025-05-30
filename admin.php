<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Connexion à la base de données
require_once('includes/db_connect.php');

// Initialiser les variables
$section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';
$allowed_sections = ['dashboard', 'users', 'experiences', 'establishments', 'settings', 'carnets'];

if (!in_array($section, $allowed_sections)) {
    $section = 'dashboard';
}

// Traitement des actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    // Traitement de la déconnexion
    if ($action === 'logout') {
        // Détruire toutes les variables de session
        $_SESSION = [];
        
        // Détruire la session
        session_destroy();
        
        // Rediriger vers la page de connexion
        header('Location: admin_login.php');
        exit();
    }
}

// Handle carnet submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_carnet') {
    try {
        $conn->beginTransaction();
        
        // Create upload directory if it doesn't exist
        $upload_dir = 'image/carnets/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Handle main carnet data with correct column names
        $stmt = $conn->prepare("INSERT INTO carnets (title, author, location, place, transport, content) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            htmlspecialchars($_POST['title']),
            htmlspecialchars($_POST['author']),
            htmlspecialchars($_POST['locations'] ?? ''),
            htmlspecialchars($_POST['place'] ?? ''),
            htmlspecialchars($_POST['transport'] ?? ''),
            htmlspecialchars($_POST['content'] ?? '')
        ]);
        
        $carnet_id = $conn->lastInsertId();
        
        // Handle locations in carnet_locations table
        if (!empty($_POST['locations'])) {
            $locations = explode(',', $_POST['locations']);
            foreach ($locations as $location) {
                $location = trim($location);
                if (!empty($location)) {
                    $stmt = $conn->prepare("INSERT INTO carnet_locations (carnet_id, location) VALUES (?, ?)");
                    $stmt->execute([$carnet_id, $location]);
                }
            }
        }
        
        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $filename = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
                    $target_path = $upload_dir . $filename;
                    
                    if (move_uploaded_file($tmp_name, $target_path)) {
                        $stmt = $conn->prepare("INSERT INTO carnet_images (carnet_id, image_path) VALUES (?, ?)");
                        $stmt->execute([$carnet_id, $target_path]);
                    }
                }
            }
        }
        
        $conn->commit();
        $_SESSION['flash_message'] = "Carnet ajouté avec succès!";
        $_SESSION['flash_type'] = "success";
        header("Location: admin.php?section=carnets");
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['flash_message'] = "Erreur: " . $e->getMessage();
        $_SESSION['flash_type'] = "error";
    }
}

// Fonction pour enregistrer l'activité de l'admin
function logAdminActivity($action, $details = '') {
    global $conn;
    
    // Use admin_id from session instead of username
    $admin_id = $_SESSION['user_id'] ?? 1; // Default to 1 if no user_id in session
    
    try {
        // Insert into admin_actions table with correct column structure
        $stmt = $conn->prepare("INSERT INTO admin_actions (admin_id, action_type, target_id, target_type) VALUES (?, ?, 0, 'system')");
        $stmt->execute([$admin_id, $action]);
    } catch (PDOException $e) {
        // Log error silently to avoid breaking the main functionality
        error_log("Admin activity logging failed: " . $e->getMessage());
    }
}

// Récupérer les statistiques pour le tableau de bord
$stats = [];

// Nombre total d'utilisateurs
$stmt = $conn->query("SELECT COUNT(*) as count FROM users");
$stats['users'] = $stmt->fetch()['count'];

// Nombre total d'expériences
$stmt = $conn->query("SELECT COUNT(*) as count FROM experiences");
$stats['experiences'] = $stmt->fetch()['count'];

// Nombre total d'établissements
$stmt = $conn->query("SELECT COUNT(*) as count FROM establishments");
$stats['establishments'] = $stmt->fetch()['count'];

// Nombre total de carnets de voyage
$stmt = $conn->query("SELECT COUNT(*) as count FROM carnets");
$stats['carnets'] = $stmt->fetch()['count'];

// Expériences récentes
$stmt = $conn->query("SELECT e.*, u.username FROM experiences e JOIN users u ON e.user_id = u.id ORDER BY e.created_at DESC LIMIT 5");
$recent_experiences = $stmt->fetchAll();

// Utilisateurs récents
$stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
$recent_users = $stmt->fetchAll();

// Carnets récents
$stmt = $conn->query("SELECT c.*, GROUP_CONCAT(DISTINCT cl.location) AS locations 
                    FROM carnets c 
                    LEFT JOIN carnet_locations cl ON c.id = cl.carnet_id 
                    GROUP BY c.id 
                    ORDER BY c.created_at DESC LIMIT 5");
$recent_carnets = $stmt->fetchAll();

// Vérifier s'il y a un message flash
$flash_message = '';
$flash_type = '';

if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    $flash_type = $_SESSION['flash_type'] ?? 'success';
    
    // Supprimer le message flash après l'avoir récupéré
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Discover DZ</title>
    <!-- Add these required Bootstrap links -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Add jQuery first -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- Then Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Then Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Then your custom scripts -->
    <script src="assets/js/admin-modals.js"></script>
    <script src="/memoire/assets/js/admin-carnets.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styleadmin.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --text-color: #333;
            --text-light: #777;
            --border-color: #ddd;
            --bg-light: #f5f5f5;
            --hover-color: #2980b9;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: var(--bg-light);
            line-height: 1.6;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: var(--transition);
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-header h2 {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-header p {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.25rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar-menu i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        .sidebar-footer a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .sidebar-footer a:hover {
            color: white;
        }
        
        .sidebar-footer i {
            margin-right: 0.5rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            transition: var(--transition);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-header h1 {
            font-size: 1.8rem;
            color: var(--primary-color);
        }
        
        .page-header .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Dashboard */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-card .icon.users {
            background-color: var(--secondary-color);
        }
        
        .stat-card .icon.experiences {
            background-color: var(--success-color);
        }
        
        .stat-card .icon.establishments {
            background-color: var(--warning-color);
        }
        
        .stat-card .icon.reports {
            background-color: var(--danger-color);
        }
        
        .stat-card .content h3 {
            font-size: 1.8rem;
            margin-bottom: 0.25rem;
        }
        
        .stat-card .content p {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h2 {
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        .card-header .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .recent-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .recent-item:last-child {
            border-bottom: none;
        }
        
        .recent-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
        }
        
        .recent-item .content {
            flex: 1;
        }
        
        .recent-item h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        
        .recent-item p {
            color: var(--text-light);
            font-size: 0.85rem;
        }
        
        .recent-item .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Tables */
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header h2 {
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th,
        table td {
            padding: 1rem 1.5rem;
            text-align: left;
        }
        
        table th {
            background-color: var(--bg-light);
            font-weight: 600;
            color: var(--primary-color);
        }
        
        table tr {
            border-bottom: 1px solid var(--border-color);
        }
        
        table tr:last-child {
            border-bottom: none;
        }
        
        table td .status {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        table td .status.active {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }
        
        table td .status.pending {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        table td .status.inactive {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }
        
        table td .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Forms */
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }
        
        .btn i {
            margin-right: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--hover-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #27ae60;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #e67e22;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-light {
            background-color: var(--bg-light);
            color: var(--text-color);
        }
        
        .btn-light:hover {
            background-color: #e0e0e0;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-warning {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
        }
        
        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert-info {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
            border-left: 4px solid var(--secondary-color);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar-header h2,
            .sidebar-header p,
            .sidebar-menu span,
            .sidebar-footer span {
                display: none;
            }
            
            .sidebar-header img {
                margin-bottom: 0;
            }
            
            .sidebar-menu a {
                justify-content: center;
                padding: 0.75rem;
            }
            
            .sidebar-menu i {
                margin-right: 0;
            }
            
            .sidebar-footer a {
                justify-content: center;
            }
            
            .sidebar-footer i {
                margin-right: 0;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid,
            .recent-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="image/logo3.jpg" alt="Discover DZ Logo">
                <h2>Discover DZ</h2>
                <p>Administration</p>
            </div>
            
            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="admin.php?section=dashboard" class="<?php echo $section === 'dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin.php?section=users" class="<?php echo $section === 'users' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin.php?section=carnets" class="<?php echo $section === 'carnets' ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i>
                            <span>Carnets de Voyage</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin.php?section=establishments" class="<?php echo $section === 'establishments' ? 'active' : ''; ?>">
                            <i class="fas fa-store"></i>
                            <span>Établissements</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin.php?section=settings" class="<?php echo $section === 'settings' ? 'active' : ''; ?>">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="sidebar-footer">
                <a href="admin.php?action=logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <?php if (!empty($flash_message)): ?>
                <div class="alert alert-<?php echo $flash_type; ?>">
                    <i class="fas fa-info-circle"></i>
                    <?php echo $flash_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section === 'dashboard'): ?>
                <!-- Dashboard Section -->
                <div class="page-header">
                    <h1>Tableau de bord</h1>
                    <div class="actions">
                        <a href="admin.php?section=dashboard&refresh=true" class="btn btn-light">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </a>
                    </div>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="icon users">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="content">
                            <h3><?php echo $stats['users']; ?></h3>
                            <p>Utilisateurs</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="icon experiences">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="content">
                            <h3><?php echo $stats['experiences']; ?></h3>
                            <p>Expériences</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="icon establishments">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="content">
                            <h3><?php echo $stats['establishments']; ?></h3>
                            <p>Établissements</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="icon" style="background-color: #9b59b6;">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="content">
                            <h3><?php echo $stats['carnets']; ?></h3>
                            <p>Carnets de voyage</p>
                        </div>
                    </div>
                </div>
                
                <div class="recent-grid">
                    <div class="card">
                        <div class="card-header">
                            <h2>Expériences récentes</h2>
                            <a href="admin.php?section=experiences" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Voir tout
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (count($recent_experiences) > 0): ?>
                                <?php foreach ($recent_experiences as $experience): ?>
                                    <div class="recent-item">
                                        <img src="<?php echo !empty($experience['cover_image']) ? $experience['cover_image'] : 'image/default-experience.jpg'; ?>" alt="Experience">
                                        <div class="content">
                                            <h4><?php echo htmlspecialchars($experience['title']); ?></h4>
                                            <p>Par <?php echo htmlspecialchars($experience['username']); ?> - <?php echo date('d/m/Y', strtotime($experience['created_at'])); ?></p>
                                        </div>
                                        <div class="actions">
                                            <a href="preview_experience.php?id=<?php echo $experience['id']; ?>" class="btn btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Aucune expérience récente.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Carnets de voyage récents</h2>
                            <a href="admin.php?section=carnets" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Voir tout
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (count($recent_carnets) > 0): ?>
                                <?php foreach ($recent_carnets as $carnet): ?>
                                    <div class="recent-item">
                                        <img src="image/logo3.jpg" alt="Carnet de voyage">
                                        <div class="content">
                                            <h4><?php echo htmlspecialchars($carnet['title']); ?></h4>
                                            <p>Par <?php echo htmlspecialchars($carnet['author']); ?> - <?php echo date('d/m/Y', strtotime($carnet['created_at'])); ?></p>
                                            <?php if (!empty($carnet['locations'])): ?>
                                                <div>
                                                    <?php 
                                                    $locations = explode(',', $carnet['locations']);
                                                    foreach ($locations as $location): ?>
                                                        <span class="badge badge-info"><?php echo htmlspecialchars($location); ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="actions">
                                            <a href="admin.php?section=carnets&action=edit&id=<?php echo $carnet['id']; ?>" class="btn btn-light">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Aucun carnet de voyage récent.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Utilisateurs récents</h2>
                            <a href="admin.php?section=users" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Voir tout
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (count($recent_users) > 0): ?>
                                <?php foreach ($recent_users as $user): ?>
                                    <div class="recent-item">
                                        <img src="<?php echo !empty($user['profile_image']) ? $user['profile_image'] : 'image/default-user.jpg'; ?>" alt="User">
                                        <div class="content">
                                            <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                                            <p>Inscrit le <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                                        </div>
                                        <div class="actions">
                                            <a href="admin.php?section=users&action=view&id=<?php echo $user['id']; ?>" class="btn btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Aucun utilisateur récent.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($section === 'carnets'): ?>
                <?php include('admin_sections/carnets.php'); ?>
            <?php elseif ($section === 'establishments'): ?>
                <?php include('admin_sections/establishments.php'); ?>
            <?php elseif ($section === 'admins'): ?>
                <?php include('admin_manage_admins.php'); ?>
            <?php else: ?>
                <?php
                // Inclure le fichier de section approprié
                $section_file = 'admin_sections/' . $section . '.php';
                if (file_exists($section_file)) {
                    include($section_file);
                } else {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Section non trouvée.</div>';
                }
                ?>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        // Script pour gérer les alertes
        document.addEventListener('DOMContentLoaded', function() {
            // Faire disparaître les alertes après 5 secondes
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>
