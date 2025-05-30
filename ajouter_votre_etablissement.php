<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover DZ - Partagez vos expériences de voyage !</title>
    <link rel="stylesheet" type="text/css" href="ajouter_votre_etablissement.css">
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
                        <a href="#" id="destination">DESTINATIONS</a>
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
                <li><div class="posi">
                     <button onclick="getLocation()">TROUVER VOTRE POSITION</button>
                     <p id="position"></p>
                   </div></li>
            </ul> 
        </nav>
        <nav class="nav2">
            <ul class="navbar2">
              <li><a href="connecter.php">SE CONNECTER</a></li> 
              <li><a href="s'inscrire.php">S'INSCRIRE</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="img"></div> <!-- image floutée -->
      
        <div class="content-row">
          <!-- Texte à gauche -->
          <div class="intro-text">
            <h1>INSCRIVER VOTRE ETABLISSEMENT SUR<br><span>DISCOVER DZ</span></h1>
            <p>Commencez maintenant, c'est simple et ne vous prendra que quelques minutes.</p>
          </div>
      
          <!-- Carte à droite -->
          <div class="card">
            <h2>Inscrire votre établissement</h2>
            <ul>
              <li><i class="fa-solid fa-check" style="color: darkblue;"></i> Développez votre activité</li>
              <li><i class="fa-solid fa-check" style="color: darkblue;"></i> Contrôlez votre réputation en ligne</li>
              <li><i class="fa-solid fa-check" style="color: darkblue;"></i> Des fonctionnalités professionnelles</li>
              <li><i class="fa-solid fa-check" style="color: darkblue;"></i> Des avis clients vérifiés</li>
              <li><i class="fa-solid fa-check" style="color: darkblue;"></i> Une assistance disponible 24h/24 et 7j/7</li>
            </ul>
            <a href="ajouter_votre_etblsmt_inscription.php"><button class="start-btn">Commencer</button></a>
            <p class="login-link">Déjà partenaire ? <a href="connecter.php">Se connecter</a></p>
          </div>
        </div>
      </div>



      <div class="titre">
        <h2>Pourquoi travailler avec nous ?</h2>
        <p>Ce que nous offrons à nos partenaires</p>
      </div>
      






      <div class="features">
        <div class="feature-card">
          <i class="fa-solid fa-globe" style="color: blue;"></i>
          <h3>Amélioration de votre présence en ligne</h3>
          <p>Mettre en avant votre établissement en ligne et vous assurer une visibilité maximale auprès des clients</p>
        </div>
        
        <div class="feature-card">
          <i class="fa-solid fa-screwdriver-wrench" style="color: blue;"></i>
          <h3>Des fonctionnalités adaptées à vos besoins</h3>
          <p>Optimisation du site web et de l'application mobile avec les derniers technologies afin d'inciter les clients à réserver en ligne</p>
        </div>
      
        <div class="feature-card">
          <i class="fa-solid fa-headphones" style="color: blue;"></i>
          <h3>Une assistance disponible 24h/24 et 7j/7</h3>
          <p>Notre équipe est à votre disposition à tout moment pour vous conseiller ou vous aider ainsi que vos clients</p>
        </div>
      
        <div class="feature-card">
          <i class="fa-solid fa-comment" style="color: blue;"></i>
          <h3>Recueillir des avis clients authentiques</h3>
          <p>Collecte et contrôle des retours d'expérience de vos clients afin de donner plus de crédibilité à votre établissement</p>
        </div>
      
        <div class="feature-card">
          <i class="fa-solid fa-chart-simple"style="color: blue;"></i>
          <h3>Surveiller les performances de votre établissement</h3>
          <p>Accès à des données statistiques et indicateurs de performance essentiels pour vous aider à faire les meilleurs choix</p>
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