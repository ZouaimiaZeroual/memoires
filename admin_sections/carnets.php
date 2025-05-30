<?php
// Verify admin access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../admin.php');
    exit();
}

// Initialize variables
$action = isset($_GET['action']) ? $_GET['action'] : '';
$carnet_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$success_message = '';
$error_message = '';

// Process actions (delete, edit, etc.)
if ($action === 'delete' && $carnet_id > 0) {
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Delete related records first (due to foreign key constraints)
        $stmt = $conn->prepare("DELETE FROM carnet_images WHERE carnet_id = :id");
        $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt = $conn->prepare("DELETE FROM carnet_locations WHERE carnet_id = :id");
        $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Delete the carnet
        $stmt = $conn->prepare("DELETE FROM carnets WHERE id = :id");
        $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        // Log admin activity
        logAdminActivity('delete_carnet', 'Suppression du carnet ID: ' . $carnet_id);
        
        $success_message = 'Le carnet a été supprimé avec succès.';
    } catch (PDOException $e) {
        // Only rollback if transaction is active
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $error_message = 'Erreur lors de la suppression du carnet: ' . $e->getMessage();
    }
}

// Get carnet for editing if ID is provided and action is edit
$editCarnet = null;
if ($action === 'edit' && $carnet_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM carnets WHERE id = :id");
    $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
    $stmt->execute();
    $editCarnet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($editCarnet) {
        // Get carnet locations
        $stmt = $conn->prepare("SELECT location FROM carnet_locations WHERE carnet_id = :id");
        $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
        $stmt->execute();
        $locations = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $editCarnet['locations'] = $locations;
        
        // Get carnet images
        $stmt = $conn->prepare("SELECT id, image_path FROM carnet_images WHERE carnet_id = :id");
        $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $editCarnet['images'] = $images;
        
        // Si c'est une requête AJAX, renvoyer les données au format JSON
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            header('Content-Type: application/json');
            // In the AJAX response section, add the missing fields:
            echo json_encode([
                'success' => true,
                'carnet' => [
                    'id' => $editCarnet['id'],
                    'title' => $editCarnet['title'],
                    'author' => $editCarnet['author'],
                    'place' => $editCarnet['place'],
                    'transport' => $editCarnet['transport'], 
                    'content' => $editCarnet['content'],
                    'locations' => $locations,
                    'images' => array_map(function($img) {
                        return [
                            'id' => $img['id'],
                            'path' => $img['image_path']
                        ];
                    }, $images)
                ]
            ]);
            exit;
        }
    } else if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
        // Si le carnet n'existe pas et que c'est une requête AJAX
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Le carnet demandé n\'existe pas.'
        ]);
        exit;
    }
}

// Process form submission for edit/add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Update existing carnet
        if ($_POST['action'] === 'edit_carnet' && isset($_POST['carnet_id'])) {
            $carnet_id = (int)$_POST['carnet_id'];
            $title = trim($_POST['title']);
            $author = trim($_POST['author']);
            
            try {
                $conn->beginTransaction();
                
                // Update carnet with only existing columns (title, author)
                // Around line 116-120, replace the existing UPDATE statement:
                $stmt = $conn->prepare("UPDATE carnets SET title = :title, author = :author, place = :place, transport = :transport, content = :content WHERE id = :id");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':author', $author);
                $stmt->bindParam(':place', trim($_POST['place']));
                $stmt->bindParam(':transport', trim($_POST['transport']));
                $stmt->bindParam(':content', trim($_POST['content']));
                $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
                $stmt->execute();
                
                // Handle locations (delete and re-insert)
                if (isset($_POST['locations'])) {
                    // Delete existing locations
                    $stmt = $conn->prepare("DELETE FROM carnet_locations WHERE carnet_id = :id");
                    $stmt->bindParam(':id', $carnet_id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    // Insert new locations
                    $locations = explode(',', $_POST['locations']);
                    foreach ($locations as $location) {
                        $location = trim($location);
                        if (!empty($location)) {
                            $stmt = $conn->prepare("INSERT INTO carnet_locations (carnet_id, location) VALUES (:carnet_id, :location)");
                            $stmt->bindParam(':carnet_id', $carnet_id, PDO::PARAM_INT);
                            $stmt->bindParam(':location', $location);
                            $stmt->execute();
                        }
                    }
                }
                
                // Handle image uploads
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $upload_dir = '../uploads/carnets/';
                    
                    // Create directory if it doesn't exist
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    // Process each uploaded file
                    $file_count = count($_FILES['images']['name']);
                    for ($i = 0; $i < $file_count; $i++) {
                        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                            $tmp_name = $_FILES['images']['tmp_name'][$i];
                            $name = basename($_FILES['images']['name'][$i]);
                            $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            
                            // Generate unique filename
                            $new_filename = 'carnet_' . $carnet_id . '_' . uniqid() . '.' . $file_ext;
                            $destination = $upload_dir . $new_filename;
                            
                            // Check if it's a valid image file
                            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                            if (in_array($file_ext, $allowed_types)) {
                                if (move_uploaded_file($tmp_name, $destination)) {
                                    // Save image path to database
                                    $image_path = 'uploads/carnets/' . $new_filename;
                                    $stmt = $conn->prepare("INSERT INTO carnet_images (carnet_id, image_path) VALUES (:carnet_id, :image_path)");
                                    $stmt->bindParam(':carnet_id', $carnet_id, PDO::PARAM_INT);
                                    $stmt->bindParam(':image_path', $image_path);
                                    $stmt->execute();
                                }
                            }
                        }
                    }
                }
                
                // Handle image deletions
                if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                    foreach ($_POST['delete_images'] as $image_id) {
                        // Get image path before deleting
                        $stmt = $conn->prepare("SELECT image_path FROM carnet_images WHERE id = :id AND carnet_id = :carnet_id");
                        $stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
                        $stmt->bindParam(':carnet_id', $carnet_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $image = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($image) {
                            // Delete file from server
                            $file_path = '../' . $image['image_path'];
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                            
                            // Delete record from database
                            $stmt = $conn->prepare("DELETE FROM carnet_images WHERE id = :id AND carnet_id = :carnet_id");
                            $stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
                            $stmt->bindParam(':carnet_id', $carnet_id, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
                
                $conn->commit();
                logAdminActivity('edit_carnet', 'Modification du carnet ID: ' . $carnet_id);
                $success_message = 'Le carnet a été mis à jour avec succès.';
            } catch (PDOException $e) {
                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }
                $error_message = 'Erreur lors de la mise à jour du carnet: ' . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'add_carnet') {
            // Add new carnet
            $title = trim($_POST['title']);
            $author = trim($_POST['author']);
            
            try {
                $conn->beginTransaction();
                
                // Insert carnet
                $stmt = $conn->prepare("INSERT INTO carnets (title, author, created_at) VALUES (:title, :author, NOW())");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':author', $author);
                $stmt->execute();
                
                $new_carnet_id = $conn->lastInsertId();
                
                // Handle locations
                if (isset($_POST['locations'])) {
                    $locations = explode(',', $_POST['locations']);
                    foreach ($locations as $location) {
                        $location = trim($location);
                        if (!empty($location)) {
                            $stmt = $conn->prepare("INSERT INTO carnet_locations (carnet_id, location) VALUES (:carnet_id, :location)");
                            $stmt->bindParam(':carnet_id', $new_carnet_id, PDO::PARAM_INT);
                            $stmt->bindParam(':location', $location);
                            $stmt->execute();
                        }
                    }
                }
                
                // Handle image uploads
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $upload_dir = '../uploads/carnets/';
                    
                    // Create directory if it doesn't exist
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    // Process each uploaded file
                    $file_count = count($_FILES['images']['name']);
                    for ($i = 0; $i < $file_count; $i++) {
                        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                            $tmp_name = $_FILES['images']['tmp_name'][$i];
                            $name = basename($_FILES['images']['name'][$i]);
                            $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            
                            // Generate unique filename
                            $new_filename = 'carnet_' . $new_carnet_id . '_' . uniqid() . '.' . $file_ext;
                            $destination = $upload_dir . $new_filename;
                            
                            // Check if it's a valid image file
                            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                            if (in_array($file_ext, $allowed_types)) {
                                if (move_uploaded_file($tmp_name, $destination)) {
                                    // Save image path to database
                                    $image_path = 'uploads/carnets/' . $new_filename;
                                    $stmt = $conn->prepare("INSERT INTO carnet_images (carnet_id, image_path) VALUES (:carnet_id, :image_path)");
                                    $stmt->bindParam(':carnet_id', $new_carnet_id, PDO::PARAM_INT);
                                    $stmt->bindParam(':image_path', $image_path);
                                    $stmt->execute();
                                }
                            }
                        }
                    }
                }
                
                $conn->commit();
                logAdminActivity('add_carnet', 'Ajout d\'un nouveau carnet ID: ' . $new_carnet_id);
                $success_message = 'Le carnet a été ajouté avec succès.';
            } catch (PDOException $e) {
                $conn->rollBack();
                $error_message = 'Erreur lors de l\'ajout du carnet: ' . $e->getMessage();
            }
        }
    }
}

// Process form submission for edit/add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_carnet'])) {
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    
    $stmt = $conn->prepare("INSERT INTO carnets (title, author) VALUES (?, ?)");
    $stmt->execute([$title, $author]);
    
    $_SESSION['flash_message'] = 'Carnet de voyage ajouté avec succès!';
    $_SESSION['flash_type'] = 'success';
    header('Location: admin.php?section=carnets');
    exit();
}

// Pagination settings
$items_per_page = 10;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Search and filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_author = isset($_GET['filter_author']) ? trim($_GET['filter_author']) : '';
$filter_location = isset($_GET['filter_location']) ? trim($_GET['filter_location']) : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';

// Validate sort parameters
$allowed_sort_fields = ['id', 'title', 'author', 'created_at', 'image_count'];
$allowed_sort_orders = ['ASC', 'DESC'];

if (!in_array($sort_by, $allowed_sort_fields)) {
    $sort_by = 'created_at';
}

if (!in_array($sort_order, $allowed_sort_orders)) {
    $sort_order = 'DESC';
}

// Build the query
$query = "SELECT 
            c.id, 
            c.title, 
            c.author, 
            c.created_at,
            GROUP_CONCAT(DISTINCT cl.location SEPARATOR ',') AS locations,
            COUNT(DISTINCT ci.id) AS image_count
        FROM carnets c
        LEFT JOIN carnet_images ci ON c.id = ci.carnet_id
        LEFT JOIN carnet_locations cl ON c.id = cl.carnet_id
        WHERE 1=1";

$params = [];

// Add search conditions
if (!empty($search)) {
    $query .= " AND (c.title LIKE :search OR c.author LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($filter_author)) {
    $query .= " AND c.author LIKE :author";
    $params[':author'] = "%$filter_author%";
}

if (!empty($filter_location)) {
    $query .= " AND cl.location LIKE :location";
    $params[':location'] = "%$filter_location%";
}

// Group by and order
$query .= " GROUP BY c.id ORDER BY $sort_by $sort_order";

// Count total results for pagination
$count_query = "SELECT COUNT(DISTINCT c.id) FROM carnets c
                LEFT JOIN carnet_locations cl ON c.id = cl.carnet_id
                WHERE 1=1";

if (!empty($search)) {
    $count_query .= " AND (c.title LIKE :search OR c.author LIKE :search)";
}

if (!empty($filter_author)) {
    $count_query .= " AND c.author LIKE :author";
}

if (!empty($filter_location)) {
    $count_query .= " AND cl.location LIKE :location";
}

$stmt = $conn->prepare($count_query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$total_items = $stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Add pagination to the main query
$query .= " LIMIT :offset, :limit";
$params[':offset'] = $offset;
$params[':limit'] = $items_per_page;

// Execute the main query
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$carnets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get unique authors for filter dropdown
$stmt = $conn->prepare("SELECT DISTINCT author FROM carnets ORDER BY author");
$stmt->execute();
$authors = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get unique locations for filter dropdown
$stmt = $conn->prepare("SELECT DISTINCT location FROM carnet_locations ORDER BY location");
$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!-- Inclusion des styles personnalisés pour les modales -->
<link rel="stylesheet" href="assets/css/admin-modals.css">

<div class="page-header">
    <h1><i class="fas fa-book"></i> Gestion des Carnets de Voyage</h1>
    <div class="actions">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCarnetModal">
            <i class="fas fa-plus"></i> Ajouter un carnet
        </button>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="fas fa-search"></i> Recherche et filtres</h5>
    </div>
    <div class="card-body">
        <form method="get" action="admin.php" class="row">
            <input type="hidden" name="section" value="carnets">
            
            <div class="col-md-3 mb-3">
                <label for="search">Recherche</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Titre ou auteur" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="filter_author">Filtrer par auteur</label>
                <select class="form-control" id="filter_author" name="filter_author">
                    <option value="">Tous les auteurs</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?php echo htmlspecialchars($author); ?>" <?php echo ($filter_author === $author) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($author); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="filter_location">Filtrer par lieu</label>
                <select class="form-control" id="filter_location" name="filter_location">
                    <option value="">Tous les lieux</option>
                    <?php foreach ($locations as $location): ?>
                        <option value="<?php echo htmlspecialchars($location); ?>" <?php echo ($filter_location === $location) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($location); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="sort_by">Trier par</label>
                <div class="input-group">
                    <select class="form-control" id="sort_by" name="sort_by">
                        <option value="created_at" <?php echo ($sort_by === 'created_at') ? 'selected' : ''; ?>>Date de création</option>
                        <option value="title" <?php echo ($sort_by === 'title') ? 'selected' : ''; ?>>Titre</option>
                        <option value="author" <?php echo ($sort_by === 'author') ? 'selected' : ''; ?>>Auteur</option>
                        <option value="image_count" <?php echo ($sort_by === 'image_count') ? 'selected' : ''; ?>>Nombre d'images</option>
                    </select>
                    <div class="input-group-append">
                        <select class="form-control" id="sort_order" name="sort_order">
                            <option value="DESC" <?php echo ($sort_order === 'DESC') ? 'selected' : ''; ?>>Décroissant</option>
                            <option value="ASC" <?php echo ($sort_order === 'ASC') ? 'selected' : ''; ?>>Croissant</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Appliquer les filtres
                </button>
                <a href="admin.php?section=carnets" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<!-- Table of carnets -->
<div class="table-container">
    <div class="table-header">
        <h2>Liste des carnets de voyage</h2>
        <span><?php echo $total_items; ?> carnets trouvés (page <?php echo $current_page; ?> sur <?php echo $total_pages; ?>)</span>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Lieux</th>
                    <th>Images</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($carnets) > 0): ?>
                    <?php foreach ($carnets as $carnet): ?>
                        <tr>
                            <td><?php echo $carnet['id']; ?></td>
                            <td><?php echo htmlspecialchars($carnet['title']); ?></td>
                            <td><?php echo htmlspecialchars($carnet['author']); ?></td>
                            <td>
                                <?php 
                                if (!empty($carnet['locations'])) {
                                    $locations = explode(',', $carnet['locations']);
                                    foreach ($locations as $location) {
                                        echo '<span class="badge badge-info">' . htmlspecialchars($location) . '</span> ';
                                    }
                                } else {
                                    echo '<span class="text-muted">Aucun lieu</span>';
                                }
                                ?>
                            </td>
                            <td><?php echo $carnet['image_count']; ?> images</td>
                            <td><?php echo date('d/m/Y H:i', strtotime($carnet['created_at'])); ?></td>
                            <td>
                                <div class="btn-group">
                                    <!-- <a href="preview_carnet.php?id=<?php echo $carnet['id']; ?>" class="btn btn-sm btn-primary" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a> -->
                                    <button type="button" class="btn btn-sm btn-info edit-carnet-btn" data-id="<?php echo $carnet['id']; ?>">
                                        <i class="fas fa-edit"></i> Éditer
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $carnet['id']; ?>, '<?php echo addslashes(htmlspecialchars($carnet['title'])); ?>')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucun carnet de voyage trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination-container mt-4">
        <nav aria-label="Navigation des pages">
            <ul class="pagination justify-content-center">
                <!-- Bouton précédent -->
                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="admin.php?section=carnets&page=<?php echo $current_page - 1; ?>&search=<?php echo urlencode($search); ?>&filter_author=<?php echo urlencode($filter_author); ?>&filter_location=<?php echo urlencode($filter_location); ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">
                        <i class="fas fa-chevron-left"></i> Précédent
                    </a>
                </li>
                
                <!-- Pages numérotées -->
                <?php 
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                if ($start_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="admin.php?section=carnets&page=1&search=<?php echo urlencode($search); ?>&filter_author=<?php echo urlencode($filter_author); ?>&filter_location=<?php echo urlencode($filter_location); ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">
                            1
                        </a>
                    </li>
                    <?php if ($start_page > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                        <a class="page-link" href="admin.php?section=carnets&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter_author=<?php echo urlencode($filter_author); ?>&filter_location=<?php echo urlencode($filter_location); ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="admin.php?section=carnets&page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>&filter_author=<?php echo urlencode($filter_author); ?>&filter_location=<?php echo urlencode($filter_location); ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">
                            <?php echo $total_pages; ?>
                        </a>
                    </li>
                <?php endif; ?>
                
                <!-- Bouton suivant -->
                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="admin.php?section=carnets&page=<?php echo $current_page + 1; ?>&search=<?php echo urlencode($search); ?>&filter_author=<?php echo urlencode($filter_author); ?>&filter_location=<?php echo urlencode($filter_location); ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">
                        Suivant <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<!-- Modal pour ajouter un carnet -->
<div class="modal fade" id="addCarnetModal" tabindex="-1" role="dialog" aria-labelledby="addCarnetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCarnetModalLabel">Nouveau Carnet de Voyage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCarnetForm" method="post" action="admin.php?section=carnets" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_carnet">
                    
                    <div class="form-group">
                        <label for="add_title">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="add_author">Auteur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_author" name="author" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="add_locations">Lieux (séparés par des virgules)</label>
                        <input type="text" class="form-control" id="add_locations" name="locations">
                    </div>
                    
                    <div class="form-group">
                        <label for="add_images">Images</label>
                        <input type="file" class="form-control-file" id="add_images" name="images[]" multiple>
                    </div>
                    
                    <div class="form-group">
                        <label for="add_place">Lieu principal</label>
                        <input type="text" class="form-control" id="add_place" name="place">
                    </div>

                    <div class="form-group">
                        <label for="add_transport">Moyen de transport</label>
                        <input type="text" class="form-control" id="add_transport" name="transport">
                    </div>

                    <div class="form-group">
                        <label for="add_content">Contenu détaillé</label>
                        <textarea class="form-control" id="add_content" name="content" rows="4"></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour éditer un carnet -->
<div class="modal fade" id="editCarnetModal" tabindex="-1" role="dialog" aria-labelledby="editCarnetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCarnetModalLabel"><i class="fas fa-edit"></i> Modifier un carnet de voyage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="admin.php?section=carnets" enctype="multipart/form-data" id="editCarnetForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_carnet">
                    <input type="hidden" name="carnet_id" id="edit_carnet_id">
                    
                    <div class="form-group">
                        <label for="edit_title">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_author">Auteur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_author" name="author" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_locations">Lieux (séparés par des virgules)</label>
                        <input type="text" class="form-control" id="edit_locations" name="locations" placeholder="Paris, Rome, Barcelone">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_place">Lieu principal</label>
                        <input type="text" class="form-control" id="edit_place" name="place">
                    </div>

                    <div class="form-group">
                        <label for="edit_transport">Moyen de transport</label>
                        <input type="text" class="form-control" id="edit_transport" name="transport">
                    </div>

                    <div class="form-group">
                        <label for="edit_content">Contenu détaillé</label>
                        <textarea class="form-control" id="edit_content" name="content" rows="4"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Images actuelles</label>
                        <div id="current_images" class="row mb-3"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_images">Ajouter de nouvelles images</label>
                        <input type="file" class="form-control-file" id="edit_images" name="images[]" multiple accept="image/*">
                        <small class="form-text text-muted">Formats acceptés: JPG, JPEG, PNG, GIF. Taille maximale: 5MB par image.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteCarnetModal" tabindex="-1" role="dialog" aria-labelledby="deleteCarnetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCarnetModalLabel"><i class="fas fa-trash-alt"></i> Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le carnet <strong id="delete_carnet_title"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Cette action est irréversible et supprimera également toutes les images associées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <a href="#" id="confirm_delete_btn" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion du script pour améliorer les modales -->
<script src="assets/js/admin-modals.js"></script>

<!-- Inclusion du script JavaScript pour la gestion des carnets -->
<script src="../assets/js/admin-carnets.js"></script>

<script>
// Fonction pour confirmer la suppression d'un carnet
function confirmDelete(id, title) {
    document.getElementById('delete_carnet_title').textContent = title;
    document.getElementById('confirm_delete_btn').href = 'admin.php?section=carnets&action=delete&id=' + id;
    $('#deleteCarnetModal').modal('show');
}

// Gestion du bouton d'édition
$(document).ready(function() {
    // Gestionnaire pour les boutons d'édition
    $('.edit-carnet-btn').on('click', function() {
        var carnetId = $(this).data('id');
        
        // Réinitialiser le formulaire
        $('#editCarnetForm')[0].reset();
        $('#current_images').empty();
        
        // Charger les données du carnet via AJAX
        $.ajax({
            url: 'admin.php?section=carnets&action=edit&id=' + carnetId + '&ajax=1',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var carnet = response.carnet;
                    
                    // Remplir le formulaire avec les données
                    $('#edit_carnet_id').val(carnet.id);
                    $('#edit_title').val(carnet.title);
                    $('#edit_author').val(carnet.author);
                    $('#edit_locations').val(carnet.locations.join(', '));
                    
                    // Afficher les images actuelles
                    if (carnet.images && carnet.images.length > 0) {
                        var imagesHtml = '';
                        carnet.images.forEach(function(image) {
                            imagesHtml += '<div class="col-md-4 mb-2">';
                            imagesHtml += '<div class="card">';
                            imagesHtml += '<img src="../' + image.path + '" class="card-img-top" alt="Image du carnet" style="max-height: 150px; object-fit: cover;">';
                            imagesHtml += '<div class="card-body p-2 text-center">';
                            imagesHtml += '<div class="custom-control custom-checkbox">';
                            imagesHtml += '<input type="checkbox" class="custom-control-input" id="delete_image_' + image.id + '" name="delete_images[]" value="' + image.id + '">';
                            imagesHtml += '<label class="custom-control-label" for="delete_image_' + image.id + '">Supprimer</label>';
                            imagesHtml += '</div>';
                            imagesHtml += '</div>';
                            imagesHtml += '</div>';
                            imagesHtml += '</div>';
                        });
                        $('#current_images').html(imagesHtml);
                    } else {
                        $('#current_images').html('<div class="col-12"><p class="text-muted">Aucune image disponible</p></div>');
                    }
                    
                    // Afficher le modal
                    $('#editCarnetModal').modal('show');
                } else {
                    alert('Erreur: ' + response.message);
                }
            },
            error: function() {
                alert('Une erreur est survenue lors du chargement des données du carnet.');
            }
        });
    });
});
</script>