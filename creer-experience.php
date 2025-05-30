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
    // Récupération des données du formulaire
    $titre = trim($_POST['titre'] ?? '');
    $wilaya = trim($_POST['wilaya'] ?? '');
    $lieu = trim($_POST['lieu'] ?? '');
    $endroit = trim($_POST['endroit'] ?? '');
    $transport = trim($_POST['transport'] ?? '');
    $commentaire = trim($_POST['commentaire'] ?? '');
    $is_private = isset($_POST['prive']) ? 1 : 0;
    $user_id = $_SESSION['user_id'];

    // Validation des données
    if (empty($titre)) {
        $error = "Le titre est obligatoire";
    } elseif (empty($wilaya)) {
        $error = "La wilaya est obligatoire";
    } else {
        try {
            // Insertion dans la base de données
            $stmt = $conn->prepare("
                INSERT INTO experiences 
                (user_id, title, content, status, created_at)
                VALUES (?, ?, ?, 'draft', NOW())
            ");
            
            // Combine all the form data into content
            $content = "Wilaya: " . $wilaya . "\n";
            $content .= "Lieu: " . $lieu . "\n";
            $content .= "Endroit: " . $endroit . "\n";
            $content .= "Transport: " . $transport . "\n";
            $content .= "Commentaire: " . $commentaire;
            
            $stmt->execute([
                $user_id,
                $titre,
                $content
            ]);
            
            $experience_id = $conn->lastInsertId();
            
            // Traitement des fichiers uploadés
            if (!empty($_FILES['photos']['name'][0])) {
                $uploadDir = 'uploads/experiences/' . $experience_id . '/';
                
                // Créer le répertoire s'il n'existe pas
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Parcourir chaque fichier
                foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                    $filename = basename($_FILES['photos']['name'][$key]);
                    $uploadFile = $uploadDir . uniqid() . '_' . $filename;
                    
                    if (move_uploaded_file($tmp_name, $uploadFile)) {
                        // Enregistrer le chemin dans la base de données
                        $stmt = $conn->prepare("
                            INSERT INTO media 
                            (uploader_user_id, target_id, target_type, file_path, file_name, mime_type, uploaded_at)
                            VALUES (?, ?, 'experience', ?, ?, ?, NOW())
                        ");
                        $mime_type = mime_content_type($uploadFile);
                        $stmt->execute([$user_id, $experience_id, $uploadFile, $filename, $mime_type]);
                    }
                }
            }
            
            // Traitement des tags
            if (!empty($_POST['tags'])) {
                $tags = explode(',', $_POST['tags']);
                $tags = array_map('trim', $tags);
                $tags = array_filter($tags);
                
                foreach ($tags as $tag) {
                    // Vérifier si le tag existe déjà
                    $stmt = $conn->prepare("SELECT id FROM tags WHERE nom = ?");
                    $stmt->execute([$tag]);
                    $tag_row = $stmt->fetch();
                    
                    if (!$tag_row) {
                        // Créer le tag s'il n'existe pas
                        $stmt = $conn->prepare("INSERT INTO tags (nom, created_at) VALUES (?, NOW())");
                        $stmt->execute([$tag]);
                        $tag_id = $conn->lastInsertId();
                    } else {
                        $tag_id = $tag_row['id'];
                    }
                    
                    // Lier le tag à l'expérience
                    $stmt = $conn->prepare("
                        INSERT INTO experience_tags 
                        (experience_id, tag_id, created_at)
                        VALUES (?, ?, NOW())
                    ");
                    $stmt->execute([$experience_id, $tag_id]);
                }
            }
            
            $success = "Votre expérience a été publiée avec succès!";
            
        } catch(PDOException $e) {
            $error = "Une erreur est survenue lors de l'enregistrement: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Discover DZ, une plateforme pour raconter et partager ses expériences!</title>
    <link rel="stylesheet" type="text/css" href="stylecreer-experience.css">
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
                      <a href="creer-experience.php" id="destination">DESTINATIONS</a>
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



   
    <h1>Partager une experience</h1>
    <div class="container">
           <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <div class="note">
            <p><strong>À noter :</strong> Vous êtes sur le point de partager une nouvelle expérience de voyage.   
                Racontez votre aventure, ajoutez des photos et des vidéos, et inspirez d'autres voyageurs !</p>
        </div>
           <form action="creer-experience.php" method="POST" enctype="multipart/form-data">
            <label for="titre"><strong>Titre de votre expérience :</strong></label>
            <input type="text" id="titre" name="titre" placeholder="Ex: Une aventure à Annaba" required value="<?php echo isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : ''; ?>">
            
            <label for="wilaya">Wilaya :</label>
            <input list="wilayas" id="wilaya" name="wilaya" placeholder="votre wilaya" required value="<?php echo isset($_POST['wilaya']) ? htmlspecialchars($_POST['wilaya']) : ''; ?>">
        <datalist id="wilayas">   <!--Le datalist est un élément HTML qui permet de créer une liste déroulante avec une option de saisie-->
               <option value="Annaba">
               <option value="Alger">
               <option value="Oran">
              <option value="Constantine">
              <option value="Tlemcen">
        </datalist>

          <label for="lieu">Lieu :</label>
            <input type="text" id="lieu" name="lieu" placeholder="Ex: Centre-ville" value="<?php echo isset($_POST['lieu']) ? htmlspecialchars($_POST['lieu']) : ''; ?>">

            <label for="endroit">Endroit :</label>
            <input type="text" id="endroit" name="endroit" placeholder="Ex: La Bouna" value="<?php echo isset($_POST['endroit']) ? htmlspecialchars($_POST['endroit']) : ''; ?>">

            <label for="transport">Moyen de transport :</label>
            <input list="transports" id="transport" name="transport" placeholder="Sélectionner ou écrire..." value="<?php echo isset($_POST['transport']) ? htmlspecialchars($_POST['transport']) : ''; ?>">
<datalist id="transports">
    <option value="Véhicule">
    <option value="Bus">
    <option value="Train">
    <option value="Avion">
</datalist>

            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire" name="commentaire" placeholder="Décris ton expérience..."><?php echo isset($_POST['commentaire']) ? htmlspecialchars($_POST['commentaire']) : ''; ?></textarea>

        <!-- Ajout de médias -->
        <label>Ajouter des photos :</label>
        <input type="file" accept="image/*" multiple>
           <label for="tags">Tags :</label>
            <input type="text" id="tags" name="tags" placeholder="Ajoutez des tags séparés par des virgules" value="<?php echo isset($_POST['tags']) ? htmlspecialchars($_POST['tags']) : ''; ?>">

        <!-- Options de publication -->
        <div class="private-option">
            <div style="display: inline-flex; align-items: center;">
                  <label for="prive"><strong>Expérience privée</strong></label>
                    <input type="checkbox" id="prive" name="prive" style="margin-left: 5px;" <?php echo isset($_POST['prive']) ? 'checked' : ''; ?>>
            </div>
            
             <p>Si vous souhaitez ne partager votre Experience qu'avec un nombre limité de personnes.</p>     
         </div>

        <button type="submit">Créer ma experience</button>
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
    </div >
    
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