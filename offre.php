
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover DZ - Partagez vos expériences de voyage !</title>
    <link rel="stylesheet" type="text/css" href="styleoffre.css">
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
               <li><a href="explore.php">EXPLORE</a></li> 
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
            <ul class="navbar2">
                <li><a href="connecter.php">SE CONNECTER</a></li> 
                <li><a href="s'inscrire.php">S'INSCRIRE</a></li>
            </ul>
        </nav>
    </div>
    <!-- Section Établissement -->
<div class="etablissement-container">
    <div class="etablissement-card">
        <div class="etablissement-image">
            <img src="image/sabri1.jpg" alt="Hôtel Example">
        </div>
        <div class="etablissement-info">
            <h3>Hôtel Example</h3>
            <div class="etablissement-rating">
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star_half</span>
                <span>(4.5/5)</span>
            </div>
            <p class="etablissement-location">
                <span class="material-symbols-outlined">location_on</span>
                Alger, Algérie
            </p>
            <p class="etablissement-description">
                Un hôtel luxueux avec vue sur la mer, offrant un service exceptionnel et des installations modernes.
            </p>
            <div class="etablissement-actions">
                <button class="btn-reserver">Réserver</button>
                <button class="btn-details">Plus de détails</button>
            </div>
        </div>
    </div>
</div>
<!-- Section Restaurant Bostan -->
<div class="etablissement-container">
    <div class="etablissement-card">
        <div class="etablissement-image">
            <img src="image/boustane.jpg" alt="Restaurant Bostan">
        </div>
        <div class="etablissement-info">
            <h3>Restaurant Bostan</h3>
            <div class="etablissement-rating">
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star</span>
                <span class="material-symbols-outlined">star</span>
                <span>(5/5)</span>
            </div>
            <p class="etablissement-location">
                <span class="material-symbols-outlined">location_on</span>
                Rue Didouche Mourad, Alger
            </p>
            <p class="etablissement-description">
                Restaurant gastronomique offrant une cuisine algérienne raffinée dans un cadre élégant. Spécialités: Couscous royal, Tadjine de viande, et pâtisseries traditionnelles.
            </p>
            <div class="etablissement-details">
                <p><strong>Horaires :</strong> 11h-23h (Fermé le lundi)</p>
                <p><strong>Prix moyen :</strong> 2500-4000 DZD</p>
                <p><strong>Spécialités :</strong> Cuisine algérienne, Plats traditionnels</p>
            </div>
            <div class="etablissement-actions">
                <button class="btn-reserver">Réserver une table</button>
                <button class="btn-details">Voir le menu</button>
            </div>
        </div>
    </div>
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