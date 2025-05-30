<?php
session_start();

// Connexion à la base de données
require_once('includes/db_connect.php');
require_once('includes/user_functions.php');

// Récupérer les expériences publiées
$stmt = $conn->prepare("SELECT e.*, u.username, l.name as location_name, l.city as location_city 
                        FROM experiences e 
                        LEFT JOIN users u ON e.user_id = u.id 
                        LEFT JOIN locations l ON e.location_id = l.id 
                        WHERE e.status = 'published' 
                        ORDER BY e.created_at DESC 
                        LIMIT 6");
$stmt->execute();
$experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les établissements approuvés
$stmt = $conn->prepare("SELECT e.*, u.username, l.name as location_name, l.city as location_city 
                        FROM establishments e 
                        LEFT JOIN users u ON e.owner_id = u.id 
                        LEFT JOIN locations l ON e.location_id = l.id 
                        WHERE e.approval_status = 'approved' 
                        ORDER BY e.created_at DESC 
                        LIMIT 6");
$stmt->execute();
$establishments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour récupérer les médias d'une expérience ou d'un établissement
function getMediaForEntity($entity_id, $entity_type, $limit = 5) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM media WHERE target_id = :target_id AND target_type = :target_type ORDER BY is_cover DESC, sort_order ASC LIMIT :limit");
    $stmt->bindParam(':target_id', $entity_id, PDO::PARAM_INT);
    $stmt->bindParam(':target_type', $entity_type, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les catégories d'une expérience ou d'un établissement
function getCategoriesForEntity($entity_id, $entity_type) {
    global $conn;
    $table = $entity_type . '_categories';
    $column = $entity_type . '_id';
    
    $stmt = $conn->prepare("SELECT c.* FROM categories c 
                           JOIN {$table} ec ON c.id = ec.category_id 
                           WHERE ec.{$column} = :entity_id");
    $stmt->bindParam(':entity_id', $entity_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore - Discover DZ</title>
    <link rel="stylesheet" type="text/css" href="styleexplore.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,1,-25">
    <link rel="stylesheet" href="icones/all.css">
</head>
<body>

    <header>
        <div class="mydiv">
            <img src="image/logo3.jpg" alt="Discover DZ Logo">
            <a href="accueil.php" style="text-decoration: none;"><h1>DISCOVER DZ</h1></a>
        </div>
        <nav class="navv">
            <ul class="navbar">
               <li><a href="explore_dynamic.php" >EXPLORE</a></li> 
               <li> <nav class="menu-container">
                <ul class="menu">
                    <li class="menu-item">
                        <a href="accueil.php" id="destination">DESTINATIONS</a>
                        <div class="submenu">
                            <ul class="submenu-column">
                                <li><strong>Nord</strong></li>
                                <li><a href="#">Alger</a></li>
                                <li><a href="#">Blida</a></li>
                                <li><a href="#">Boumerdès</a></li>
                                <li><a href="#">Tipaza</a></li>
                                <li><a href="#">Tizi Ouzou</a></li>
                                <li><a href="#">Béjaïa</a></li>
                                <li><a href="#">Jijel</a></li>
                                <li><a href="#">Skikda</a></li>
                            </ul>
                            
                            <!-- Autres colonnes du menu déroulant -->
                        </div>
                    </li>
                </ul>
            </nav>
        </li>
               <li><a href="offre.php">OFFRE</a></li>
               <li><a href="ajouter_votre_etablissement.php">AJOUTER VOTRE ETABLISSEMENT</a></li>
            </ul>
        </nav>
        <div class="myi">
            <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f icon-btn"></i></a>
            <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram icon-btn"></i></a>
        </div>
    </header>

    <div class="div1">
        <nav class="nav1">
            <ul class="navbar1">
                <li><a href="creer-carnet.php">ÉCRIRE SON CARNET DE VOYAGE</a></li> 
                <li><a href="creer-experience.php">ÉCRIRE SON EXPÉRIENCE</a></li>
                <div class="posi" >
                    <button onclick="getLocation()">TROUVER VOTRE POSITION</button>
                    <p id="position"></p>
                </div>
            </ul>
        </nav>
        <nav class="nav2">
            <ul class="navbar2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profil.php">MON PROFIL</a></li>
                    <li><a href="logout.php">DÉCONNEXION</a></li>
                <?php else: ?>
                    <li><a href="connecter.php">SE CONNECTER</a></li> 
                    <li><a href="s'inscrire.php">S'INSCRIRE</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <div class="explore">
        <div class="explore-header">
            <h1>Explorez les récits de voyage</h1>
        </div>

        <div class="search-container">
            <input type="text" placeholder="Rechercher des destinations, des expériences...">
            <button><i class="fas fa-search"></i> Rechercher</button>
        </div>

        <section class="carnets">
            <h2>Expériences de voyage récentes</h2>
            <div class="carnets-container">
                <?php foreach ($experiences as $index => $experience): 
                    // Récupérer les médias de l'expérience
                    $media = getMediaForEntity($experience['id'], 'experience');
                    // Récupérer les catégories de l'expérience
                    $categories = getCategoriesForEntity($experience['id'], 'experience');
                ?>
                <div class="carnet">
                    <div class="carnet-img-placeholder">
                        <button class="prev" onclick="afficherImagePrecedente(<?= $index ?>)">&#10094;</button>
                        <?php if (!empty($media)): 
                            foreach ($media as $img): ?>
                                <img src="<?= htmlspecialchars($img['file_path']) ?>" alt="<?= htmlspecialchars($experience['title']) ?>">
                            <?php endforeach; 
                        else: ?>
                            <img src="image/default_experience.jpg" alt="Image par défaut">
                        <?php endif; ?>
                        <button class="next" onclick="afficherImageSuivante(<?= $index ?>)">&#10095;</button>
                    </div>
                    <div class="carnet-info">
                        <div class="badges-container">
                            <?php if (!empty($categories)): 
                                foreach ($categories as $category): ?>
                                    <span class="badge"><?= htmlspecialchars($category['name']) ?></span>
                                <?php endforeach; 
                            endif; ?>
                            <?php if (!empty($experience['location_name'])): ?>
                                <span class="badge"><?= htmlspecialchars($experience['location_name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($experience['title']) ?></h3>
                        <p class="author">par <span><?= htmlspecialchars($experience['username']) ?></span></p>
                        <a href="view_experience.php?id=<?= $experience['id'] ?>" class="bouton">ouvrir l'expérience</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="establishments">
            <h2>Établissements recommandés</h2>
            <div class="establishments-container">
                <?php foreach ($establishments as $establishment): 
                    // Récupérer les médias de l'établissement
                    $media = getMediaForEntity($establishment['id'], 'establishment', 1);
                    // Récupérer les catégories de l'établissement
                    $categories = getCategoriesForEntity($establishment['id'], 'establishment');
                ?>
                <div class="establishment-card">
                    <div class="establishment-img">
                        <?php if (!empty($media)): ?>
                            <img src="<?= htmlspecialchars($media[0]['file_path']) ?>" alt="<?= htmlspecialchars($establishment['name']) ?>">
                        <?php else: ?>
                            <img src="image/default_establishment.jpg" alt="Image par défaut">
                        <?php endif; ?>
                    </div>
                    <div class="establishment-info">
                        <div class="badges-container">
                            <span class="badge"><?= htmlspecialchars($establishment['type']) ?></span>
                            <?php if (!empty($categories)): 
                                foreach ($categories as $category): ?>
                                    <span class="badge"><?= htmlspecialchars($category['name']) ?></span>
                                <?php endforeach; 
                            endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($establishment['name']) ?></h3>
                        <?php if (!empty($establishment['location_name'])): ?>
                            <p class="location"><?= htmlspecialchars($establishment['location_name']) ?>, <?= htmlspecialchars($establishment['location_city']) ?></p>
                        <?php endif; ?>
                        <a href="view_establishment.php?id=<?= $establishment['id'] ?>" class="bouton">voir les détails</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <script>
        // Script pour le carrousel d'images
        let indexActuels = [];
        
        function afficherImage(carnetIndex, nouvelleIndex) {
            const carnet = document.querySelectorAll('.carnet')[carnetIndex];
            const images = carnet.querySelectorAll('img');
            
            // Initialiser l'index si nécessaire
            if (!indexActuels[carnetIndex]) {
                indexActuels[carnetIndex] = 0;
            }
            
            // Gérer les bornes
            if (nouvelleIndex < 0) {
                nouvelleIndex = images.length - 1;
            } else if (nouvelleIndex >= images.length) {
                nouvelleIndex = 0;
            }
            
            // Cacher toutes les images
            images.forEach(img => img.style.display = 'none');
            
            // Afficher la bonne image
            if (images[nouvelleIndex]) {
                images[nouvelleIndex].style.display = 'block';
            }
            
            // Mettre à jour l'index
            indexActuels[carnetIndex] = nouvelleIndex;
        }
        
        function afficherImagePrecedente(carnetIndex) {
            afficherImage(carnetIndex, (indexActuels[carnetIndex] || 0) - 1);
        }
        
        function afficherImageSuivante(carnetIndex) {
            afficherImage(carnetIndex, (indexActuels[carnetIndex] || 0) + 1);
        }
        
        // Initialisation : afficher uniquement la première image de chaque carnet
        window.onload = () => {
            document.querySelectorAll('.carnet').forEach((carnet, index) => {
                const images = carnet.querySelectorAll('img');
                images.forEach((img, i) => img.style.display = i === 0 ? 'block' : 'none');
                indexActuels[index] = 0;
            });
        };
        
        // Fonction pour la géolocalisation
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                document.getElementById("position").innerHTML = "La géolocalisation n'est pas prise en charge par ce navigateur.";
            }
        }
        
        function showPosition(position) {
            document.getElementById("position").innerHTML = "Latitude: " + position.coords.latitude + 
            "<br>Longitude: " + position.coords.longitude;
        }
    </script>
</body>
</html>