<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <link rel="stylesheet" href="styleprofil.css">
    
</head>
<body>
    <?php
    require_once('db_connect.php');
    session_start();

    // Get author from localStorage using JavaScript and store in session
    echo '<script>
    // Listen for changes in localStorage
    window.addEventListener("storage", function(e) {
        if (e.key === "selectedAuthor") {
            const author = e.newValue;
            if (author) {
                // Store the new author data
                fetch("store_author.php?author=" + encodeURIComponent(author))
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to show new data
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            }
        }
    });

    // Initial load
    document.addEventListener("DOMContentLoaded", function() {
        const author = localStorage.getItem("selectedAuthor");
        if (author) {
            fetch("store_author.php?author=" + encodeURIComponent(author))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        }
    });
</script>';

    // Get author from session
    $author = isset($_SESSION['selectedAuthor']) ? $_SESSION['selectedAuthor'] : null;

    if ($author) {
        try {
            // Fetch author's data
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
                        COUNT(DISTINCT c.id) as total_carnets
                    FROM carnets c
                    LEFT JOIN carnet_images ci ON c.id = ci.carnet_id
                    WHERE c.author = ?
                    GROUP BY c.id
                    ORDER BY c.created_at DESC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$author]);
            $carnets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total stats
            $totalCarnets = count($carnets);
            $totalPhotos = 0;
            foreach ($carnets as $carnet) {
                if ($carnet['images']) {
                    $totalPhotos += count(explode(',', $carnet['images']));
                }
            }

            // Get member since date (first carnet)
            $firstCarnetQuery = "SELECT MIN(created_at) as first_carnet FROM carnets WHERE author = ?";
            $stmt = $pdo->prepare($firstCarnetQuery);
            $stmt->execute([$author]);
            $firstCarnet = $stmt->fetch(PDO::FETCH_ASSOC);
            $memberSince = $firstCarnet['first_carnet'];

            // Get last publication date
            $lastCarnetQuery = "SELECT MAX(created_at) as last_carnet FROM carnets WHERE author = ?";
            $stmt = $pdo->prepare($lastCarnetQuery);
            $stmt->execute([$author]);
            $lastCarnet = $stmt->fetch(PDO::FETCH_ASSOC);
            $lastPublication = $lastCarnet['last_carnet'];

        } catch(PDOException $e) {
            echo '<div class="error">Erreur de chargement des données: ' . htmlspecialchars($e->getMessage()) . '</div>';
            $carnets = [];
            $totalCarnets = 0;
            $totalPhotos = 0;
            $memberSince = null;
            $lastPublication = null;
        }
    }
    ?>
    
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
                        <a href="profil.php" id="destination">DESTINATIONS</a>
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
                                <li><a href="annaba.php">Annaba</a></li>
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
        <select class="select" id="type" name="type" class="navbar2">
            <option value="nom_utilisateur">Nom d'utilisateur</option>
            <option value="conducteur">Déconnecter</option>
        </select>
      </nav>
    </div>

   
    
    <div id="map"></div>
    
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([28, 2], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    </script>

<section class="profile">
    <div class="profile-picture">
        <img    src="image/photo_profil.jpg" alt="">
    </div>
</section>

<section class="profile-info">
    <h2 id="authorName"></h2>
    <button class="edit-btn">Modifier mon profil</button>
    <p id="memberInfo"></p>
</section>

<section class="stats">
    <div class="stat-box" id="totalCarnets">0<br>Voyage raconté</div>
    <div class="stat-box" id="totalEtapes">0<br>Étape créée</div>
    <div class="stat-box" id="totalPhotos">0<br>Photo publiée</div>
    <div class="stat-box">0<br>Commentaire</div>
    <div class="stat-box">0<br>Questions/Réponses</div>
</section>


        <!-- MODALE DES CARNETS -->
        <div id="carnetModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="fermerModal('carnetModal')">&times;</span>
                <h3>Choisissez un carnet :</h3>
                <?php if (!empty($carnets)): ?>
                    <?php foreach ($carnets as $carnet): ?>
                        <div class="carnet" onclick="selectionnerCarnet('<?php echo htmlspecialchars($carnet['title']); ?>')">
                            <?php echo htmlspecialchars($carnet['title']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun carnet disponible</p>
                <?php endif; ?>
            </div>
        </div>
    
        <!-- Fenêtre modale -->
        <div id="modal" class="modal">
            <div class="bare_défilement">
            <div class="modal-content">
                <span class="close" onclick="fermerModal('modal')">&times;</span>
                <h2>Ajouter une étape</h2>
                
                <p id="nomCarnetSelectionne"><strong>Carnet :</strong> </p>
              <form action="">
                <div class="form-group">
                <label for="titre">Titre de votre étape</label>
                <input type="text" id="titre" placeholder="Ex : Visite de la Casbah d'Alger">
              </div>

              <div class="form-group">
                <label for="date">Quand s'est-elle déroulée ?</label>
                <input type="date" id="date">
            </div>

            <div class="form-group">
                <label for="wilaya">Wilaya</label>
                <select id="wilaya">
                    <option value="" disabled selected>Choisissez une wilaya</option>
                    <option>Adrar</option>
                    <option>Chlef</option>
                    <option>Laghouat</option>
                    <option>Oum El Bouaghi</option>
                    <option>Batna</option>
                    <option>Béjaïa</option>
                    <option>Biskra</option>
                    <option>Béchar</option>
                    <option>Blida</option>
                    <option>Bouira</option>
                    <option>Tamanrasset</option>
                    <option>Tébessa</option>
                    <option>Tlemcen</option>
                    <option>Tiaret</option>
                    <option>Tizi Ouzou</option>
                    <option>Alger</option>
                    <option>Djelfa</option>
                    <option>Jijel</option>
                    <option>Sétif</option>
                    <option>Saïda</option>
                    <option>Skikda</option>
                    <option>Sidi Bel Abbès</option>
                    <option>Annaba</option>
                    <option>Guelma</option>
                    <option>Constantine</option>
                    <option>Médéa</option>
                    <option>Mostaganem</option>
                    <option>MSila</option>
                    <option>Mascara</option>
                    <option>Ouargla</option>
                    <option>Oran</option>
                    <option>El Bayadh</option>
                    <option>Illizi</option>
                    <option>Bordj Bou Arreridj</option>
                    <option>Boumerdès</option>
                    <option>El Tarf</option>
                    <option>Tindouf</option>
                    <option>Tissemsilt</option>
                    <option>El Oued</option>
                    <option>Khenchela</option>
                    <option>Souk Ahras</option>
                    <option>Tipaza</option>
                    <option>Mila</option>
                    <option>Aïn Defla</option>
                    <option>Naâma</option>
                    <option>Aïn Témouchent</option>
                    <option>Ghardaïa</option>
                    <option>Relizane</option>
                    <option>Timimoun</option>
                    <option>Bordj Badji Mokhtar</option>
                    <option>Ouled Djellal</option>
                    <option>Béni Abbès</option>
                    <option>In Salah</option>
                    <option>In Guezzam</option>
                    <option>Touggourt</option>
                    <option>Djanet</option>
                    <option>El MGhair</option>
                    <option>El Menia</option>
                </select>
            </div>

                <div class="form-group">
                <label for="lieu">Lieu précis</label>
                <input type="text" id="lieu" placeholder="Ex : Casbah d'Alger, Ghardaïa">
               </div>

               <div class="form-group">
                <label for="transport">Quel moyen de transport ?</label>
                <input type="text" id="transport" placeholder="Ex : Avion, Train, Bus">
               </div>

               <div class="form-group">
                <label for="resume">Résumé de l'étape</label>
                <textarea id="resume" placeholder="Décris ton étape ici..."></textarea>
               </div>
               <div class="form-group">
                <!-- Ajout de médias -->
        <label>Ajouter des photos :</label>
        <input type="file" accept="image/*" multiple>

        
    </div>
                <button id="saveStep">Enregistrer</button>
        </form>   
     </div>
    </div>
        </div>
     
        <script>
            function selectionnerCarnet(nomCarnet) {
                document.getElementById("modal").style.display = "flex";
                document.getElementById("nomCarnetSelectionne").innerHTML = `<strong>Carnet :</strong> ${nomCarnet}`;
                fermerModal('carnetModal');
            }
    
            function fermerModal(id) {
                document.getElementById(id).style.display = "none";
            }
    
            function ouvrirModal(id) {
                document.getElementById(id).style.display = "flex";
            }
    
            window.onclick = function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = "none";
                }
            }
        </script>
      
    
<!-- carte experience -------------------------------------------------------------------------- -->
<?php if (!empty($carnets)): ?>
    <?php foreach ($carnets as $carnet): ?>
        <div class="experience-card" data-carnet-id="<?php echo htmlspecialchars($carnet['id']); ?>">
            <div class="experience-info">
                <h2><?php echo htmlspecialchars($carnet['title']); ?></h2>
                <p class="author">par <span class="author-name"><?php echo htmlspecialchars($carnet['author']); ?></span></p>
                <p class="location"><strong>Lieu :</strong> <?php echo htmlspecialchars($carnet['location']); ?></p>
                <p class="place"><strong>Endroit :</strong> <?php echo htmlspecialchars($carnet['place']); ?></p>
                <p class="transport"><strong>Moyen de transport :</strong> <?php echo htmlspecialchars($carnet['transport']); ?></p>
                <p class="description_carte"><?php echo htmlspecialchars($carnet['content']); ?></p>
                <div class="button-group">
                    <button class="btn edit-btn" onclick="modifierCarnet(<?php echo htmlspecialchars($carnet['id']); ?>)">Modifier</button>
                    <button class="btn delete-btn" onclick="supprimerCarnet(<?php echo htmlspecialchars($carnet['id']); ?>)">Supprimer</button>
                </div>
            </div>

            <?php if (!empty($carnet['images'])): ?>
            <div class="experience-gallery">
                <div class="image-slider">
                    <button class="prev" onclick="prevImage()">&#10094;</button>
                    <?php 
                    $images = explode(',', $carnet['images']);
                    foreach ($images as $index => $img): 
                    ?>
                        <img src="image/<?php echo htmlspecialchars($img); ?>" 
                             alt="Expérience à <?php echo htmlspecialchars($carnet['location']); ?>" 
                             onclick="openLightbox(<?php echo $index; ?>)">
                    <?php endforeach; ?>
                    <button class="next" onclick="nextImage()">&#10095;</button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="no-carnets">Aucun carnet trouvé pour cet auteur.</p>
<?php endif; ?>

<!-- carnets -------------------------------------------------------------------------- -->

<section class="carnets">
    <div class="carnets-container">
        <?php
        if (!empty($carnets)) {
            foreach ($carnets as $carnet) {
                $images = $carnet['images'] ? explode(',', $carnet['images']) : [];
                ?>
                <div class="carnet">
                    <div class="carnet-img-placeholder">
                        <button class="prev" onclick="afficherImagePrecedente(<?php echo $carnet['id']; ?>)">&#10094;</button>
                        <?php foreach ($images as $img) { ?>
                            <img src="image/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($carnet['title']); ?>">
                        <?php } ?>
                        <button class="next" onclick="afficherImageSuivante(<?php echo $carnet['id']; ?>)">&#10095;</button>
                    </div>
                    <div class="carnet-info">
                        <div class="badges-container">
                            <span class="badge"><?php echo htmlspecialchars($carnet['location']); ?></span>
                        </div>
                        <h3><?php echo htmlspecialchars($carnet['title']); ?></h3>
                        <p class="author">par <span><?php echo htmlspecialchars($carnet['author']); ?></span></p>
                        <button onclick="ouvrirFenetre(<?php echo $carnet['id']; ?>)" class="bouton">ouvrir le carnet</button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p class="no-carnets">Aucun carnet trouvé pour cet auteur.</p>';
        }
        ?>
    </div>
</section>


<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <span class="close" onclick="closeLightbox()">&times;</span>
    <button class="lightbox-prev" onclick="lightboxPrev()">&#10094;</button>
    <img id="lightbox-img" src="">
    <button class="lightbox-next" onclick="lightboxNext()">&#10095;</button>
</div>

<!-- java carte experience -------------------------------------------------------------------------- -->
<script>
    let index = 0;
    let images = document.querySelectorAll(".image-slider img");
    let lightboxIndex = 0;

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

    
    // Fonction pour supprimer la carte
    document.querySelector(".delete-btn").addEventListener("click", function() {
        const card = this.closest(".experience-card");
        card.remove(); // Supprime la carte du DOM
    });

    // Fonction pour modifier la carte
    document.querySelector(".edit-btn").addEventListener("click", function() {
        const card = this.closest(".experience-card");
        const description = card.querySelector(".description_carte");

        let newText = prompt("Modifier la description :", description.textContent);
        if (newText !== null) {
            description.textContent = newText; // Met à jour la description
        }
    });

</script>
<!-------- bouton Modifier -------------------------------------------->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editButton = document.querySelector(".edit-btn");
        const form = document.querySelector("form");
        
        // Récupérer les champs du formulaire
        const titreInput = document.getElementById("titre");
        const wilayaInput = document.getElementById("wilaya");
        const lieuInput = document.getElementById("lieu");
        const endroitInput = document.getElementById("endroit");
        const transportInput = document.getElementById("transport");
        const commentaireInput = document.getElementById("commentaire");
    
        // Récupérer les données de la carte
        const experienceTitle = document.querySelector(".experience-info h2").textContent;
        const author = document.querySelector(".author-name").textContent;
        const lieu = document.querySelector(".location strong").nextSibling.textContent.trim();
        const endroit = document.querySelector(".place strong").nextSibling.textContent.trim();
        const transport = document.querySelector(".transport strong").nextSibling.textContent.trim();
        const description = document.querySelector(".description_carte").textContent.trim();
    
        // Remplir le formulaire avec les données existantes
        editButton.addEventListener("click", function() {
            titreInput.value = experienceTitle;
            wilayaInput.value = lieu;
            lieuInput.value = "";
            endroitInput.value = endroit;
            transportInput.value = transport;
            commentaireInput.value = description;
    
            // Afficher le formulaire
            form.style.display = "block";
        });
    
        // Modifier les valeurs de la carte après soumission
        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Empêcher le rechargement de la page
    
            // Mettre à jour les valeurs de la carte
            document.querySelector(".experience-info h2").textContent = titreInput.value;
            document.querySelector(".location strong").nextSibling.textContent = " " + wilayaInput.value;
            document.querySelector(".place strong").nextSibling.textContent = " " + endroitInput.value;
            document.querySelector(".transport strong").nextSibling.textContent = " " + transportInput.value;
            document.querySelector(".description_carte").textContent = commentaireInput.value;
    
            // Cacher le formulaire après modification
            form.style.display = "none";
        });
    });
    </script>
    <script>
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
        
        </script>
        

<!-- java pour carnets  -->
<script>
    let indexActuels = [0, 0, 0]; // Un index pour chaque carnet
 
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
     });
 };
 
     </script>
     
    

 <!--- la fenetre modale---> 


 <div id="fenetreExperience" class="fenetre-experiences">
    <span class="fermer-fenetre" onclick="fermerFenetre()">&times;</span>
    <div class="contenu-fenetre">
      <div id="conteneur-experiences">
        <?php if (!empty($carnets)): ?>
            <?php foreach ($carnets as $carnet): ?>
                <div class="profil-utilisateur">
                    <img src="image/photo_profil.jpg" alt="Photo de profil" class="photo-profil" />
                    <div>
                        <p class="titre-carnet"><?php echo htmlspecialchars($carnet['title']); ?></p>
                        <p class="infos-carnet">par <strong><?php echo htmlspecialchars($carnet['author']); ?></strong> | 
                            Créé le: <?php echo date('d/m/Y', strtotime($carnet['created_at'])); ?></p>
                        <p class="description"><?php echo htmlspecialchars($carnet['content']); ?></p>
                    </div>
                </div>

                <hr />

                <div class="carte-experience">
                    <div class="infos-experience">
                        <h2><?php echo htmlspecialchars($carnet['title']); ?></h2>
                        <p class="date"><strong>Date :</strong> <?php echo date('d/m/Y', strtotime($carnet['created_at'])); ?></p>
                        <p class="wilaya"><strong>Wilaya :</strong> <?php echo htmlspecialchars($carnet['location']); ?></p>
                        <p class="lieu"><strong>Lieu :</strong> <?php echo htmlspecialchars($carnet['place']); ?></p>
                        <p class="transport"><strong>Moyen de transport :</strong> <?php echo htmlspecialchars($carnet['transport']); ?></p>
                        <p class="description_carte"><?php echo htmlspecialchars($carnet['content']); ?></p>
                    </div>
                    <?php if (!empty($carnet['images'])): ?>
                    <div class="galerie-experience">
                        <div class="diapo-images">
                            <button class="precedent">&#10094;</button>
                            <?php 
                            $images = explode(',', $carnet['images']);
                            foreach ($images as $index => $img): 
                            ?>
                                <img src="image/<?php echo htmlspecialchars($img); ?>" 
                                     class="<?php echo $index === 0 ? 'active' : ''; ?>" 
                                     onclick="ouvrirLightbox(<?php echo $index; ?>, event)" />
                            <?php endforeach; ?>
                            <button class="suivant">&#10095;</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-carnets">Aucun carnet trouvé pour cet auteur.</p>
        <?php endif; ?>
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

  <script>
      // Ouverture et fermeture de la fenêtre modale
      function ouvrirFenetre() {
        document.getElementById('fenetreExperience').style.display = 'flex';
      }
    
      function fermerFenetre() {
        document.getElementById('fenetreExperience').style.display = 'none';
      }
    
      // Carrousel d'images dans les cartes
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
    
      // Lightbox
      let imagesLightbox = [];
      let indexLightbox = 0;
    
      function ouvrirLightbox(index, event) {
const diapoActif = event.target.closest('.diapo-images');
imagesLightbox = Array.from(diapoActif.querySelectorAll('img'));
indexLightbox = index;

document.getElementById('image-lightbox').src = imagesLightbox[index].src;
document.getElementById('lightbox').style.display = 'flex';
}
    
      function fermerLightbox() {
        document.getElementById('lightbox').style.display = 'none';
      }
    
      function imageSuivante() {
        indexLightbox = (indexLightbox + 1) % imagesLightbox.length;
        document.getElementById('image-lightbox').src = imagesLightbox[indexLightbox].src;
      }
    
      function imagePrecedente() {
        indexLightbox = (indexLightbox - 1 + imagesLightbox.length) % imagesLightbox.length;
        document.getElementById('image-lightbox').src = imagesLightbox[indexLightbox].src;
      }
    </script>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <span class="close" onclick="closeLightbox()">&times;</span>
    <button class="lightbox-prev" onclick="lightboxPrev()">&#10094;</button>
    <img id="lightbox-img" src="">
    <button class="lightbox-next" onclick="lightboxNext()">&#10095;</button>
</div>
        
    

    <footer class="footer">
        <div class="footer-container">
          <!-- Contact -->
          <div class="footer-column">
              <h3>Contactez-nous</h3>
              <div class="footer-social">
                <a href="https://www.facebook.com/">  <i class="fa-brands fa-facebook-f" style="color:  darkblue;"></i></a>
                <a href="https://www.instagram.com/">  <i class="fa-brands fa-instagram"  style="color:  darkblue;"></i></a>
                <a href="https://www.gmail.com/"> <i class="fa-solid fa-envelope"  style="color:  darkblue;"></i></a>
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
        <script src="icones/all.js"></script>
        <script src="script1.js"></script>
    
      </body>
      </html>

<script>
    // Function to handle carnet modification
    function modifierCarnet(id) {
        const card = document.querySelector(`[data-carnet-id="${id}"]`);
        const title = card.querySelector('h2').textContent;
        const content = card.querySelector('.description_carte').textContent;
        const location = card.querySelector('.location').textContent.replace('Lieu :', '').trim();
        const place = card.querySelector('.place').textContent.replace('Endroit :', '').trim();
        const transport = card.querySelector('.transport').textContent.replace('Moyen de transport :', '').trim();

        // Create a modal for editing
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close" onclick="this.parentElement.parentElement.remove()">&times;</span>
                <h2>Modifier le carnet</h2>
                <form id="editForm">
                    <div class="form-group">
                        <label for="editTitle">Titre</label>
                        <input type="text" id="editTitle" value="${title}" required>
                    </div>
                    <div class="form-group">
                        <label for="editLocation">Lieu</label>
                        <input type="text" id="editLocation" value="${location}" required>
                    </div>
                    <div class="form-group">
                        <label for="editPlace">Endroit</label>
                        <input type="text" id="editPlace" value="${place}" required>
                    </div>
                    <div class="form-group">
                        <label for="editTransport">Moyen de transport</label>
                        <input type="text" id="editTransport" value="${transport}" required>
                    </div>
                    <div class="form-group">
                        <label for="editContent">Description</label>
                        <textarea id="editContent" required>${content}</textarea>
                    </div>
                    <button type="submit">Enregistrer</button>
                </form>
            </div>
        `;

        document.body.appendChild(modal);

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const data = {
                id: id,
                title: document.getElementById('editTitle').value,
                location: document.getElementById('editLocation').value,
                place: document.getElementById('editPlace').value,
                transport: document.getElementById('editTransport').value,
                content: document.getElementById('editContent').value
            };

            try {
                const response = await fetch('modifier_carnet.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (result.success) {
                    // Update the card content
                    card.querySelector('h2').textContent = data.title;
                    card.querySelector('.location').textContent = `Lieu : ${data.location}`;
                    card.querySelector('.place').textContent = `Endroit : ${data.place}`;
                    card.querySelector('.transport').textContent = `Moyen de transport : ${data.transport}`;
                    card.querySelector('.description_carte').textContent = data.content;
                    
                    modal.remove();
                    alert('Carnet modifié avec succès !');
                } else {
                    alert('Erreur lors de la modification : ' + result.message);
                }
            } catch (error) {
                alert('Erreur lors de la modification : ' + error.message);
            }
        });
    }

    // Function to handle carnet deletion
    async function supprimerCarnet(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce carnet ?')) {
            try {
                const response = await fetch('supprimer_carnet.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Remove the card from the DOM
                    const card = document.querySelector(`[data-carnet-id="${id}"]`);
                    card.remove();
                    alert('Carnet supprimé avec succès !');
                } else {
                    alert('Erreur lors de la suppression : ' + result.message);
                }
            } catch (error) {
                alert('Erreur lors de la suppression : ' + error.message);
            }
        }
    }
</script>

<script>
    // Function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR');
    }

    // Function to calculate time difference
    function getTimeDifference(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffMonths = Math.floor(diffTime / (1000 * 60 * 60 * 24 * 30));
        return diffMonths;
    }

    // Function to create experience card
    function createExperienceCard(carnet) {
        const images = carnet.images ? carnet.images.split(',') : [];
        const imagesHtml = images.map((img, index) => `
            <img src="image/${img}" 
                 alt="Expérience à ${carnet.location}" 
                 onclick="openLightbox(${index})">
        `).join('');

        return `
            <div class="experience-card" data-carnet-id="${carnet.id}">
                <div class="experience-info">
                    <h2>${carnet.title}</h2>
                    <p class="author">par <span class="author-name">${carnet.author}</span></p>
                    <p class="location"><strong>Lieu :</strong> ${carnet.location}</p>
                    <p class="place"><strong>Endroit :</strong> ${carnet.place}</p>
                    <p class="transport"><strong>Moyen de transport :</strong> ${carnet.transport}</p>
                    <p class="description_carte">${carnet.content}</p>
                    <div class="button-group">
                        <button class="btn edit-btn" onclick="modifierCarnet(${carnet.id})">Modifier</button>
                        <button class="btn delete-btn" onclick="supprimerCarnet(${carnet.id})">Supprimer</button>
                    </div>
                </div>
                ${images.length > 0 ? `
                    <div class="experience-gallery">
                        <div class="image-slider">
                            <button class="prev" onclick="prevImage()">&#10094;</button>
                            ${imagesHtml}
                            <button class="next" onclick="nextImage()">&#10095;</button>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }

    // Function to load data
    async function loadData() {
        try {
            const response = await fetch('get_carnets.php');
            const result = await response.json();

            if (result.success) {
                const { carnets, stats } = result.data;
                
                // Update profile info
                document.getElementById('authorName').textContent = carnets[0]?.author || '';
                document.getElementById('memberInfo').textContent = 
                    `Membre depuis ${getTimeDifference(stats.memberSince)} mois, dernière publication : ${formatDate(stats.lastPublication)}.`;

                // Update stats
                document.getElementById('totalCarnets').innerHTML = `${stats.totalCarnets}<br>Voyage raconté`;
                document.getElementById('totalEtapes').innerHTML = `${carnets.length}<br>Étape créée`;
                document.getElementById('totalPhotos').innerHTML = `${stats.totalPhotos}<br>Photo publiée`;

                // Update experience cards
                const cardsContainer = document.getElementById('experienceCards');
                cardsContainer.innerHTML = carnets.map(carnet => createExperienceCard(carnet)).join('');

                // Initialize image sliders
                initializeImageSliders();
            } else {
                console.error('Error loading data:', result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Initialize image sliders
    function initializeImageSliders() {
        let index = 0;
        let images = document.querySelectorAll(".image-slider img");

        function showImage(n) {
            if (n >= images.length) index = 0;
            if (n < 0) index = images.length - 1;
            images.forEach(img => img.style.display = "none");
            images[index].style.display = "block";
        }

        window.nextImage = () => showImage(++index);
        window.prevImage = () => showImage(--index);
        
        if (images.length > 0) {
            showImage(index);
        }
    }

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', loadData);

    // ... rest of your existing JavaScript code for modals, lightbox, etc. ...
</script>