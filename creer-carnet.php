<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connecter.php");
    exit();
}

// Connexion à la base de données
require_once('includes/db_connect.php');
// La connexion à la base de données est maintenant disponible via $conn

// Traitement du formulaire
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $personnes = $_POST['personnes'] ?? 'Seul';
    $duree = $_POST['duree'] ?? 'Quelques jours';
    $date_debut = $_POST['date_debut'] ?? null;
    $is_private = isset($_POST['prive']) ? 1 : 0;
    $user_id = $_SESSION['user_id'];

    // Validation
    if (empty($titre)) {
        $error = "Le titre est obligatoire";
    } else {
        try {
            // Insertion dans la base de données
            $stmt = $conn->prepare("
                INSERT INTO carnets 
                (title, author, created_at)
                VALUES (?, ?, NOW())
            ");
            
            // Get username for author field
            $user_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $user_stmt->execute([$user_id]);
            $user = $user_stmt->fetch();
            $author = $user['username'] ?? 'Unknown User';
            
            $stmt->execute([
                $titre,
                $author
            ]);
            
            $carnet_id = $conn->lastInsertId();
            $success = "Votre carnet de voyage a été créé avec succès!";
            
        } catch(PDOException $e) {
            $error = "Une erreur est survenue lors de la création du carnet: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Discover DZ, une plateforme pour raconter et partager ses expériences!</title>
    <link rel="stylesheet" type="text/css" href="stylecreer-carnet.css">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,1,-25"
    />
    <link rel="stylesheet" href="icones/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <header>
        <div class="mydiv">
          <img class="magg" src="image/logo3.jpg">
          <a href="accueil.php" style="text-decoration: none;"><h1>DISCOVER DZ</h1></a>
        </div>
        <nav class="navv">
          <ul class="navbar">
             <li><a href="explore.php" >EXPLORE</a></li> 
             <li> <nav class="menu-container">
              <ul class="menu">
                  <li class="menu-item">
                      <a href="creer-carnet.php" id="destination">DESTINATIONS</a>
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



    <h1>Créer un voyage</h1>
    <div class="container">
    <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    <div class="note">
        <p><strong>À noter :</strong> Vous êtes sur le point de créer un nouveau <strong>voyage</strong>. Si vous avez déjà créé un voyage et souhaitez ajouter du contenu, utilisez l'option "Ajouter une étape".</p>
    </div>
       <form method="POST" action="creer-carnet.php">
            <label for="titre">Donnez un titre à votre voyage</label>
            <input type="text" id="titre" name="titre" placeholder="Entrez le titre de votre aventure" required
                   value="<?php echo isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : ''; ?>">
                  <label for="description">Décrivez brièvement votre voyage</label>
            <textarea id="description" name="description" placeholder="Entrez une courte description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

        
                    <label for="personnes">Nombre de personnes</label>
            <select id="personnes" name="personnes">
                <option value="Seul" <?php echo (isset($_POST['personnes']) && $_POST['personnes'] === 'Seul') ? 'selected' : ''; ?>>Seul</option>
                <option value="À deux" <?php echo (isset($_POST['personnes']) && $_POST['personnes'] === 'À deux') ? 'selected' : ''; ?>>À deux</option>
                <option value="En groupe" <?php echo (isset($_POST['personnes']) && $_POST['personnes'] === 'En groupe') ? 'selected' : ''; ?>>En groupe</option>
            </select>

               <label for="duree">Durée</label>
            <select id="duree" name="duree">
                <option value="Quelques jours" <?php echo (isset($_POST['duree']) && $_POST['duree'] === 'Quelques jours') ? 'selected' : ''; ?>>Quelques jours</option>
                <option value="Une semaine" <?php echo (isset($_POST['duree']) && $_POST['duree'] === 'Une semaine') ? 'selected' : ''; ?>>Une semaine</option>
                <option value="Plus d'un mois" <?php echo (isset($_POST['duree']) && $_POST['duree'] === "Plus d'un mois") ? 'selected' : ''; ?>>Plus d'un mois</option>
            </select>
            
            <label for="date_debut">Début du voyage</label>
            <input type="date" id="date_debut" name="date_debut" 
                   value="<?php echo isset($_POST['date_debut']) ? htmlspecialchars($_POST['date_debut']) : ''; ?>">
        <div class="private-option">
                <div style="display: inline-flex; align-items: center;">
                    <label for="prive"><strong>Voyage privé</strong></label>
                    <input type="checkbox" id="prive" name="prive" style="margin-left: 5px;" 
                           <?php echo isset($_POST['prive']) ? 'checked' : ''; ?>>
                </div>
            <p>Si vous souhaitez ne partager votre voyage qu'avec un nombre limité de personnes.</p>
            
        </div>
        
        
        <button type="submit" class="submit">Créer mon voyage</button>
    </form>

</div>
<div class="info-droite">
    <div class="info-box">
        <div class="thumb-container">
            <div class="thumb-circle">
                <i class="fa-solid fa-thumbs-up"></i>
            </div>
        </div>
        <p>En quelques clics, ajoutez un nouveau voyage puis créez une à une les étapes qui le composent, facilement, et gratuitement.</p>
        <p><strong>Bon voyage !</strong></p>
    </div>
    
    <img style="width: 90%; margin-top: 50px;" src="image/logo3.jpg" alt="">
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