<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore - Discover DZ</title>
    <link rel="stylesheet" type="text/css" href="styleexplore.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,1,-25">
    <link rel="stylesheet" href="icones/all.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .search-form {
            display: flex;
            gap: 10px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .search-form input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .search-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .search-form button:hover {
            background-color: #0056b3;
        }
        
        .no-results {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin: 20px 0;
            color: #666;
        }
    </style>
</head>
<body>

    <header>
        <div class="mydiv">
            <img src="image/logo3.jpg" alt="Discover DZ Logo">
            <a href="accueil.php" style="text-decoration: none;"><h1>DISCOVER DZ</h1></a>
        </div>
        <nav class="navv">
            <ul class="navbar">
               <li><a href="explore.php" >EXPLORE</a></li> 
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
                            
                            <ul class="submenu-column">
                                <li><strong>Est</strong></li>
                                <li><a href="#">Constantine</a></li>
                                <li><a href="annaba.html">Annaba</a></li>
                                <li><a href="#">Guelma</a></li>
                                <li><a href="#">Oum El Bouaghi</a></li>
                                <li><a href="#">Tébessa</a></li>
                                <li><a href="#">Souk Ahras</a></li>
                                <li><a href="#">Khenchela</a></li>
                                <li><a href="#">Batna</a></li>
                            </ul>
        
                            <ul class="submenu-column">
                                <li><strong>Ouest</strong></li>
                                <li><a href="#">Oran</a></li>
                                <li><a href="#">Tlemcen</a></li>
                                <li><a href="#">Aïn Témouchent</a></li>
                                <li><a href="#">Mostaganem</a></li>
                                <li><a href="#">Relizane</a></li>
                                <li><a href="#">Mascara</a></li>
                                <li><a href="#">Sidi Bel Abbès</a></li>
                                <li><a href="#">Saïda</a></li>
                            </ul>
        
                            <ul class="submenu-column">
                                <li><strong>Sud</strong></li>
                                <li><a href="#">Adrar</a></li>
                                <li><a href="#">Tamanrasset</a></li>
                                <li><a href="#">Illizi</a></li>
                                <li><a href="#">Djanet</a></li>
                                <li><a href="#">Bordj Badji Mokhtar</a></li>
                                <li><a href="#">Timimoun</a></li>
                                <li><a href="#">Tindouf</a></li>
                                <li><a href="#">In Salah</a></li>
                            </ul>
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
                    <div class="posi" >
                        <button onclick="getLocation()">TROUVER VOTRE POSITION</button>
                          <p id="position"></p>
                      </div>
                </div>
            </ul>
        </nav>
        <nav class="nav2">
            <ul class="navbar2">
                <li><a href="connecter.php">SE CONNECTER</a></li> 
                <li><a href="s'inscrire.php">S'INSCRIRE</a></li>
            </ul>
        </nav>
    </div>
<div class="explore">
    <div class="explore-header">
        <h1>Explorez les récits de voyage</h1>
    </div>

    <div class="search-container">
        <form method="GET" action="explore.php" class="search-form">
            <input type="text" name="search" placeholder="Rechercher des destinations, des expériences..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
        </form>
    </div>
   
<section class="carnets">
    <h2>Sélection de carnets de voyage et expériences </h2>
    <div class="carnets-container">
        <?php
        try {
            require_once('db_connect.php');
            
            $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
            $params = [];
            
            $query = "SELECT 
                        c.id, 
                        c.title, 
                        c.author, 
                        c.created_at,
                        c.location,
                        c.place,
                        c.transport,
                        c.content,
                        GROUP_CONCAT(DISTINCT ci.image_path) AS images, 
                        GROUP_CONCAT(DISTINCT cl.location) AS locations
                    FROM carnets c
                    LEFT JOIN carnet_images ci ON c.id = ci.carnet_id
                    LEFT JOIN carnet_locations cl ON c.id = cl.carnet_id";
            
            if (!empty($searchQuery)) {
                $query .= " WHERE c.title LIKE ? OR c.author LIKE ? OR cl.location LIKE ?";
                $searchParam = "%" . $searchQuery . "%";
                $params = [$searchParam, $searchParam, $searchParam];
            }
            
            $query .= " GROUP BY c.id ORDER BY c.created_at DESC";
            
            $stmt = $pdo->prepare($query);
            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            $carnets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Transform grouped strings to arrays
            foreach ($carnets as &$carnet) {
                $carnet['images'] = $carnet['images'] ? explode(',', $carnet['images']) : [];
                $carnet['locations'] = $carnet['locations'] ? explode(',', $carnet['locations']) : [];
            }
        } catch(PDOException $e) {
            echo '<div class="error">Erreur de chargement des carnets: ' . htmlspecialchars($e->getMessage()) . '</div>';
            $carnets = [];
        }

        foreach ($carnets as $index => $carnet) {
            echo '<div class="carnet">
                <div class="carnet-img-placeholder">
                    <button class="prev" onclick="afficherImagePrecedente(' . $index . ')">&#10094;</button>';
            
            foreach ($carnet['images'] as $img) {
                echo '<img src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($carnet['title']) . '">';
            }
            
            echo '<button class="next" onclick="afficherImageSuivante(' . $index . ')">&#10095;</button>
                </div>
                <div class="carnet-info">
                    <div class="badges-container">';
            
            foreach ($carnet['locations'] as $location) {
                echo '<span class="badge">' . htmlspecialchars($location) . '</span>';
            }
            
            echo '</div>
                    <h3>' . htmlspecialchars($carnet['title']) . '</h3>
                    <p class="author">par <span>' . htmlspecialchars($carnet['author']) . '</span></p>
                    <button onclick="ouvrirFenetre(' . $carnet['id'] . ')" class="bouton">ouvrir le carnet</button>
                </div>
            </div>';
        }
        ?>
    </div>
</section>
    
    
    <!-- carte experience -------------------------------------------------------------------------- -->
    <section class="experiences">
        <h2>Expériences partagées</h2>
        <?php
        $searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
        
        // You can also fetch experiences from database if you have an experiences table
        $experiences = [
            [
                'location' => 'Annaba',
                'place' => 'Lala Bouna',
                'author' => 'Ahmed Ahmed',
                'transport' => 'Voiture',
                'description' => 'Lors de ma visite à Lala Bouna à Annaba, j\'ai été émerveillé par la beauté du lieu. Entouré d\'une nature luxuriante et bercé par la brise marine, j\'ai immédiatement ressenti une sensation de sérénité.',
                'images' => ['lala_bouna1.jpg', 'lala_bouna2.jpg', 'lala_bouna3.jpg', 'lala_bouna4.jpg', 'lala_bouna5.jpg']
            ]
        ];

        // Filter experiences based on search query
        if (!empty($searchQuery)) {
            $experiences = array_filter($experiences, function($exp) use ($searchQuery) {
                return strpos(strtolower($exp['location']), $searchQuery) !== false ||
                       strpos(strtolower($exp['place']), $searchQuery) !== false ||
                       strpos(strtolower($exp['author']), $searchQuery) !== false ||
                       strpos(strtolower($exp['description']), $searchQuery) !== false;
            });
        }

        if (empty($experiences)) {
            echo '<div class="no-results">Aucun résultat trouvé pour votre recherche.</div>';
        }

        foreach ($experiences as $experience) {
            echo '<div class="experience-card">
            <div class="experience-info">
                <h2>Une aventure à ' . htmlspecialchars($experience['location']) . '</h2>
                <p class="author">par <span class="author-name">' . htmlspecialchars($experience['author']) . '</span></p>
                <p class="location"><strong>Lieu :</strong> ' . htmlspecialchars($experience['location']) . '</p>
                <p class="place"><strong>Endroit :</strong> ' . htmlspecialchars($experience['place']) . '</p>
                <p class="transport"><strong>Moyen de transport :</strong> ' . htmlspecialchars($experience['transport']) . '</p>
                <p class="description_carte">"' . htmlspecialchars($experience['description']) . '"</p>
                <button class="btn" onclick="goToProfile(\'' . htmlspecialchars($experience['author']) . '\')">Accéder au profil</button>
            </div>
            <div class="experience-gallery">
                <div class="image-slider">
                    <button class="prev" onclick="prevImage()">&#10094;</button>';
            
            foreach ($experience['images'] as $i => $img) {
                echo '<img src="' . htmlspecialchars($img) . '" alt="Expérience à ' . htmlspecialchars($experience['location']) . '" onclick="openLightbox(' . $i . ')">';
            }
            
            echo '<button class="next" onclick="nextImage()">&#10095;</button>
                </div>
            </div>
            </div>';
        }
        ?>
    </section>
    
    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
    <span class="close" onclick="closeLightbox()">&times;</span>
    <button class="lightbox-prev" onclick="lightboxPrev()">&#10094;</button>
    <img id="lightbox-img" src="">
    <button class="lightbox-next" onclick="lightboxNext()">&#10095;</button>
    </div>
    
    <!-- Modal Window -->
    <div id="fenetreExperience" class="fenetre-experiences">
      <span class="fermer-fenetre" onclick="fermerFenetre()">&times;</span>
      <div class="contenu-fenetre">
        <div id="conteneur-experiences">
          <!-- Content will be loaded dynamically -->
        </div>
      </div>
    </div>
  
    <!-- Lightbox -->
    <div id="lightbox">
      <span class="fermer-lightbox" onclick="fermerLightbox()">&times;</span>
      <button class="nav-lightbox precedent-lightbox" onclick="imagePrecedente()">&#10094;</button>
      <img id="image-lightbox" />
      <button class="nav-lightbox suivant-lightbox" onclick="imageSuivante()">&#10095;</button>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>Contactez-nous</h3>
                <div class="footer-social">
                    <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f" style="color: darkblue;"></i></a>
                    <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram" style="color: darkblue;"></i></a>
                    <a href="https://www.gmail.com/"><i class="fa-solid fa-envelope" style="color: darkblue;"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h2>Liens utiles</h2>
                <ul>
                    <li><a href="#">Trouver votre localisation</a></li>
                    <li><a href="offre.php">Offre</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h2>À propos</h2>
                <p>DiscoverDZ vous permet de raconter vos aventures et vous aide à les partager avec vos amis et d'autres passionnés de voyages.</p>
            </div>
            <div class="footer-section">
                <h2>Wilayas à découvrir</h2>
                <ul>
                    <li><a href="alger.php">Alger</a></li>
                    <li><a href="annaba.php">Annaba</a></li>
                    <li><a href="constantine.php">Constantine</a></li>
                    <li><a href="mostaganem.php">Mostaganem</a></li>
                    <li><a href="#">...</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="icones/all.js"></script>
    <script src="script1.js"></script>

    <!-- JavaScript from explore2.php -->
    <script>
    let index = 0;
    let images = document.querySelectorAll(".image-slider img");
    
    function showImage(n) {
        if (n >= images.length) index = 0;
        if (n < 0) index = images.length - 1;
        images.forEach(img => img.style.display = "none");
        images[index].style.display = "block";
    }
    
    function nextImage() { showImage(++index); }
    function prevImage() { showImage(--index); }
    document.addEventListener("DOMContentLoaded", () => showImage(index));
    
    // Lightbox
    function openLightbox(n) {
        lightboxIndex = n;
        document.getElementById("lightbox-img").src = images[n].src;
        document.getElementById("lightbox").style.display = "flex";
    }
    
    function closeLightbox() {
        document.getElementById("lightbox").style.display = "none";
    }
    
    function lightboxNext() {
        lightboxIndex = (lightboxIndex + 1) % images.length;
        document.getElementById("lightbox-img").src = images[lightboxIndex].src;
    }
    
    function lightboxPrev() {
        lightboxIndex = (lightboxIndex - 1 + images.length) % images.length;
        document.getElementById("lightbox-img").src = images[lightboxIndex].src;
    }
    
    // Fermer la lightbox avec Échap et naviguer avec les flèches du clavier
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeLightbox();
        } else if (event.key === "ArrowRight") {
            lightboxNext();
        } else if (event.key === "ArrowLeft") {
            lightboxPrev();
        }
    });
    
    document.addEventListener("DOMContentLoaded", function () {
    let galerieContainers = document.querySelectorAll(".carnet-img-placeholder");
    
    galerieContainers.forEach(galerie => {
        let imagesGalerie = galerie.querySelectorAll("img");
        let indexActuel = 0;
    
        // Afficher la première image par défaut
        imagesGalerie[indexActuel].classList.add("active");
    
        // Fonction pour afficher l'image suivante
        galerie.querySelector(".next").addEventListener("click", function () {
            imagesGalerie[indexActuel].classList.remove("active");
            indexActuel = (indexActuel + 1) % imagesGalerie.length; // Passer à l'image suivante (boucle)
            imagesGalerie[indexActuel].classList.add("active");
        });
    
        // Fonction pour afficher l'image précédente
        galerie.querySelector(".prev").addEventListener("click", function () {
            imagesGalerie[indexActuel].classList.remove("active");
            indexActuel = (indexActuel - 1 + imagesGalerie.length) % imagesGalerie.length; // Revenir à l'image précédente (boucle)
            imagesGalerie[indexActuel].classList.add("active");
        });
    });
    });
    
    // Carnets image navigation
    let indexActuels = [];
    
    function afficherImage(carnetIndex, nouvelleIndex) {
        const carnet = document.querySelectorAll('.carnet')[carnetIndex];
        const images = carnet.querySelectorAll('img');
    
        // Gérer les bornes
        if (nouvelleIndex < 0) {
            nouvelleIndex = images.length - 1;
        } else if (nouvelleIndex >= images.length) {
            nouvelleIndex = 0;
        }
    
        // Cacher toutes les images
        images.forEach(img => img.style.display = 'none');
    
        // Afficher la bonne image
        images[nouvelleIndex].style.display = 'block';
    
        // Mettre à jour l'index
        indexActuels[carnetIndex] = nouvelleIndex;
    }
    
    function afficherImagePrecedente(carnetIndex) {
        afficherImage(carnetIndex, indexActuels[carnetIndex] - 1);
    }
    
    function afficherImageSuivante(carnetIndex) {
        afficherImage(carnetIndex, indexActuels[carnetIndex] + 1);
    }
    
    // Initialisation : afficher uniquement la première image de chaque carnet
    window.onload = () => {
        document.querySelectorAll('.carnet').forEach((carnet, index) => {
            const images = carnet.querySelectorAll('img');
            images.forEach((img, i) => img.style.display = i === 0 ? 'block' : 'none');
            indexActuels[index] = 0;
        });
    };
    
    // Modal functionality
    function ouvrirFenetre(carnetId) {
        // Fetch carnet details via AJAX
        fetch('get_carnet_details.php?id=' + carnetId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const carnet = data.carnet;
                    const container = document.getElementById('conteneur-experiences');
                    
                    // Create HTML content with empty data handling
                    let html = `
                        <div class="profil-utilisateur">
                            <img src="image/photo_profil.jpg" alt="Photo de profil" class="photo-profil" />
                            <div>
                                <p class="titre-carnet">${carnet.title || 'Titre non spécifié'}</p>
                                <p class="infos-carnet">par <strong>${carnet.author || 'Auteur inconnu'}</strong> | Créé le: ${carnet.created_at ? new Date(carnet.created_at).toLocaleDateString() : 'Date non spécifiée'}</p>
                                <p class="description1">${carnet.content || 'Aucune description disponible'}</p>
                            </div>
                        </div>

                        <hr />

                        <div class="carte-experience">
                            <div class="infos-experience">
                                <h2>${carnet.title }</h2>
                                <p class="date"><strong>Date :</strong> ${carnet.created_at ? new Date(carnet.created_at).toLocaleDateString() : 'Non spécifiée'}</p>
                                <p class="wilaya"><strong>Wilaya :</strong> ${carnet.location }</p>
                                <p class="lieu"><strong>Lieu :</strong> ${carnet.place || 'Non spécifié'}</p>
                                <p class="transport"><strong>Moyen de transport :</strong> ${carnet.transport || 'Non spécifié'}</p>
                                <p class="description_carte">${carnet.content || 'Aucune description disponible'}</p>
                            </div>
                            <div class="galerie-experience">
                                <div class="diapo-images">
                                    <button class="precedent">&#10094;</button>
                                    <img src="image/constantine_pont_sidi_mcid.jpg" class="active" onclick="ouvrirLightbox(0, event)" />
                                    <img src="image/constantine_vue_pont.jpg" onclick="ouvrirLightbox(1, event)" />
                                    <button class="suivant">&#10095;</button>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="carte-experience">
                            <div class="infos-experience">
                                <h2>Visite du Palais Ahmed Bey</h2>
                                <p class="date"><strong>Date :</strong> 16 Mai 2025</p>
                                <p class="wilaya"><strong>Wilaya :</strong> Constantine</p>
                                <p class="lieu"><strong>Lieu :</strong> Palais Ahmed Bey</p>
                                <p class="transport"><strong>Moyen de transport :</strong> À pied</p>
                                <p class="description_carte">"Le palais Ahmed Bey est un joyau de l'architecture ottomane en Algérie. Les détails des mosaïques et des boiseries sont d'une finesse remarquable."</p>
                            </div>
                            <div class="galerie-experience">
                                <div class="diapo-images">
                                    <button class="precedent">&#10094;</button>
                                    <img src="image/constantine_palais_ahmed_bey.jpg" class="active" onclick="ouvrirLightbox(2, event)" />
                                    <img src="image/constantine_palais_interieur.jpg" onclick="ouvrirLightbox(3, event)" />
                                    <button class="suivant">&#10095;</button>
                                </div>
                            </div>
                        </div>

                        <button class="btn" onclick="goToProfile('${carnet.author}')">Accéder au profil</button>`;

                    container.innerHTML = html;
                    document.getElementById('fenetreExperience').style.display = 'flex';

                    // Initialize image carousel for this card
                    initializeImageCarousel();
                } else {
                    alert('Erreur lors du chargement du carnet');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors du chargement du carnet');
            });
    }
  
    function fermerFenetre() {
        document.getElementById('fenetreExperience').style.display = 'none';
    }
    
    // Function to initialize image carousel
    function initializeImageCarousel() {
        document.querySelectorAll('.diapo-images').forEach((diapo) => {
            let index = 0;
            const images = diapo.querySelectorAll('img');
            const precedent = diapo.querySelector('.precedent');
            const suivant = diapo.querySelector('.suivant');

            function afficherImage(i) {
                images.forEach((img, idx) => {
                    img.classList.toggle('active', idx === i);
                });
            }

            precedent.addEventListener('click', () => {
                index = (index - 1 + images.length) % images.length;
                afficherImage(index);
            });

            suivant.addEventListener('click', () => {
                index = (index + 1) % images.length;
                afficherImage(index);
            });
        });
    }
    
    // Geolocation function
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            document.getElementById("position").innerHTML = "Geolocation is not supported by this browser.";
        }
    }
    
    function showPosition(position) {
        document.getElementById("position").innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
    }

    function goToProfile(author) {
        // Clear all existing data in localStorage
        localStorage.clear();
        console.log("author", author);
        // Store the new author data
        localStorage.setItem('selectedAuthor', author);
        sessionStorage.clear();

        console.log("author2", author);
        window.location.href = 'profil.php';
    }
    </script>

    <!-- Add CSS styles for the existing layout -->
    <style>
        .fenetre-experiences {
            background: rgba(0, 0, 0, 0.8);
        }

        .contenu-fenetre {
            max-width: 900px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
        }

        .profil-utilisateur {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .photo-profil {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .titre-carnet {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        .infos-carnet {
            color: #666;
            margin: 5px 0;
        }

        .description1 {
            color: #888;
            font-style: italic;
        }

        .carte-experience {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            overflow: hidden;
        }

        .infos-experience {
            padding: 20px;
        }

        .infos-experience h2 {
            color: #333;
            margin: 0 0 15px 0;
        }

        .infos-experience p {
            margin: 10px 0;
            color: #444;
        }

        .infos-experience strong {
            color: #666;
        }

        .description_carte {
            font-style: italic;
            color: #555;
            margin-top: 15px;
        }

        .galerie-experience {
            background: #f8f9fa;
            padding: 20px;
        }

        .diapo-images {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .diapo-images img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            display: none;
        }

        .diapo-images img.active {
            display: block;
        }

        .precedent, .suivant {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 18px;
        }

        .precedent:hover, .suivant:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn a {
            color: white;
            text-decoration: none;
        }
    </style>

</body>
</html>