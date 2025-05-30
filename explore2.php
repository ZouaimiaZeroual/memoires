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
        <input type="text" placeholder="Rechercher des destinations, des expériences...">
        <button><i class="fas fa-search"></i> Rechercher</button>
    </div>
    <!-- Inclure Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Script pour afficher la carte -->
<script>
    var map = L.map('map').setView([36.90, 7.77], 12); // Coordonnées d'Annaba
   
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
   
    L.marker([36.90, 7.77]).addTo(map)
        .bindPopup('Annaba, Algérie')
        .openPopup();
   </script>
   
<section class="carnets">
    <h2>Sélection de carnets de voyage et expériences </h2>
    <div class="carnets-container">
       <!-- Carnet 0 -->
       <div class="carnet">
        <div class="carnet-img-placeholder">
            <button class="prev" onclick="afficherImagePrecedente(0)">&#10094;</button>
            <img src="image/constantine_pont_sidi_mcid.jpg" alt="Pont Sidi Mcid">
            <img src="image/constantine_gorges.jpg" alt="Gorges du Rhummel">
            <img src="image/constantine_vieille_ville.jpg" alt="Vieille ville">
            <button class="next" onclick="afficherImageSuivante(0)">&#10095;</button>
        </div>
        <div class="carnet-info">
            <div class="badges-container">
            <span class="badge">Constantine</span>
            <span class="badge">Batna</span>
           </div>
            <h3>Découverte de l'Est algérien</h3>
            <p class="author">par <span>Amine K.</span></p>
            <button onclick="ouvrirFenetre()" class="bouton">ouvrir le carnet</button>
        </div>
    </div>
    
        <!-- Carnet 1 -->
        <div class="carnet">
            <div class="carnet-img-placeholder">
                <button class="prev" onclick="afficherImagePrecedente(1)">&#10094;</button>
                <img src="image/annaba_carnet.jpg" alt="">
                <img src="image/batna_carnet.jpg" alt="">
                <button class="next" onclick="afficherImageSuivante(1)">&#10095;</button>
            </div>
            <div class="carnet-info">
                <div class="badges-container">
                <span class="badge">Annaba</span>
                <span class="badge">Batna</span>
              </div>
                <h3>Mes Beaux Souvenirs</h3>
                <p class="author">par <span>Samira Harket</span></p>
                <button onclick="ouvrirFenetre()" class="bouton">ouvrir le carnet</button>
            </div>
        </div>
    
        <!-- Carnet 2 -->
        <div class="carnet">
            <div class="carnet-img-placeholder">
                <button class="prev" onclick="afficherImagePrecedente(2)">&#10094;</button>
                <img src="image/annaba_carnet_gare.jpg" alt="">
                <img src="image/Bejaia_carnet.jpg" alt="">
                <img src="image/setif_carnet.jpg" alt="">
                <button class="next" onclick="afficherImageSuivante(2)">&#10095;</button>
            </div>
            <div class="carnet-info">
                <div class="badges-container">
                <span class="badge">Annaba</span>
                <span class="badge">Setif</span>
                <span class="badge">Béjaïa</span>
                <span class="badge">Ghardaïa</span>
                <span class="badge">Ghardaïa</span>
            </div>
                <h3>Road-trip 2024 : entre plages, montagnes et dunes</h3>
                <p class="author">par <span>Bouguera_22</span></p>
                <button onclick="ouvrirFenetre()" class="bouton">ouvrir le carnet</button>
            </div>
        </div>
    
    </div>
    </section>
    
    
    <!-- carte experience -------------------------------------------------------------------------- -->
    <div class="experience-card">
    <div class="experience-info">
        <h2>Une aventure à Annaba</h2>
        <p class="author">par <span class="author-name">Ahmed Ahmed</span></p>
        <p class="location"><strong>Lieu :</strong> Annaba</p>
        <p class="place"><strong>Endroit :</strong> Lala Bouna</p>
        <p class="transport"><strong>Moyen de transport :</strong> Voiture</p>
        <p class="description_carte">
            "Lors de ma visite à Lala Bouna à Annaba, j’ai été émerveillé par la beauté du lieu. 
            Entouré d’une nature luxuriante et bercé par la brise marine, j’ai immédiatement ressenti une sensation de sérénité."
        </p>
        <button  class="btn"> <a href="profil.php">Accéder au profil </a></button>
    </div>
    <div class="experience-gallery">
        <div class="image-slider">
            <button class="prev" onclick="prevImage()">&#10094;</button>
            <img src="image/lala_bouna1.jpg" alt="Expérience à Annaba" onclick="openLightbox(0)">
            <img src="image/lala_bouna2.jpg" alt="Expérience à Annaba" onclick="openLightbox(1)">
            <img src="image/lala_bouna3.jpg" alt="Expérience à Annaba" onclick="openLightbox(2)">
            <img src="image/lala_bouna4.jpg" alt="Expérience à Annaba" onclick="openLightbox(3)">
            <img src="image/lala_bouna5.jpg" alt="Expérience à Annaba" onclick="openLightbox(4)">
            <button class="next" onclick="nextImage()">&#10095;</button>
        </div>
    </div>
    </div>
    
    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
    <span class="close" onclick="closeLightbox()">&times;</span>
    <button class="lightbox-prev" onclick="lightboxPrev()">&#10094;</button>
    <img id="lightbox-img" src="">
    <button class="lightbox-next" onclick="lightboxNext()">&#10095;</button>
    </div>
    
    <!-- java pour la carte  -->
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
     
         // Mettre à jour l’index
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
               <div class="profil-utilisateur">
                 <img src="image/photo_profil.jpg" alt="Photo de profil" class="photo-profil" />
                 <div>
                    <p class="titre-carnet">Aventure à Constantine</p>
                    <p class="infos-carnet">par <strong>Karim B.</strong> | Durée : 2 jours | Début : 15 Mai 2025</p>
                    <p class="description1">"Un court séjour pour découvrir les merveilles architecturales de Constantine."</p>
                 </div>
               </div>
       
               <hr />

               <div class="carte-experience">
                <div class="infos-experience">
                  <h2>Une journée inoubliable à Constantine</h2>
                  <p class="date"><strong>Date :</strong> 15 Mai 2025</p>
                  <p class="wilaya"><strong>Wilaya :</strong> Constantine</p>
                  <p class="lieu"><strong>Lieu :</strong> Pont Sidi Mcid</p>
                  <p class="transport"><strong>Moyen de transport :</strong> Taxi</p>
                  <p class="description_carte">"Le pont Sidi Mcid offre une vue à couper le souffle sur les gorges du Rhummel. C'est l'un des plus hauts ponts suspendus d'Afrique avec une vue panoramique impressionnante sur toute la ville."</p>
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
               <button  class="btn"> <a href="profil.php">Accéder au profil </a></button>
               
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
    <script>
        // Script pour les filtres
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                // Ici, vous ajouteriez le code pour filtrer les cartes
            });
        });
    </script>
</body>
</html>