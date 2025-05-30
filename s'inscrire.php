<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Discover DZ, une plateforme pour raconter et partager ses expériences!</title>
    <link rel="stylesheet" type="text/css" href="styles'inscrire.css">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,1,-25"
    />
    <link rel="stylesheet" href="icones/all.css">
</head>
<body>
<?php
ob_start(); // Démarre la mise en tampon de sortie

// Connexion à la base de données
require_once('includes/db_connect.php');

// Fonction pour obtenir une connexion à la base de données (pour compatibilité avec le code existant)
function getDBConnection() {
    global $conn;
    return $conn;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Récupération des données
    $email = trim($_POST['email'] ?? '');
    $pseudo = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);

    // Validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse email invalide.";
    }

    if (empty($pseudo) || strlen($pseudo) > 50) {
        $errors[] = "Le pseudo est requis et doit faire moins de 50 caractères.";
    }

    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (!$terms) {
        $errors[] = "Vous devez accepter les conditions d'utilisation.";
    }

    // Si pas d'erreurs, procéder à l'inscription
    if (empty($errors)) {
        try {
            $conn = getDBConnection();

            // Vérifier si email ou username existent déjà
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $pseudo]);

            if ($stmt->rowCount() > 0) {
                $errors[] = "Cet email ou pseudo est déjà utilisé.";
            } else {
                // Hash du mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insertion
                $stmt = $conn->prepare("INSERT INTO users 
                    (email, username, password_hash, created_at, updated_at) 
                    VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

                $stmt->execute([$email, $pseudo, $hashed_password]);

                // ✅ Redirection vers la page d'accueil
                header("Location: accueil.php");
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur serveur : " . $e->getMessage();
        }
    }

    // Affichage des erreurs si nécessaire
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
}

ob_end_flush(); // Libère le tampon
?>
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
                        <a href="s'inscrire.php" id="destination">DESTINATIONS</a>
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
                <li><a href="creer-experience.php">ÉCRIRE SON EXPERIENCE</a></li>
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
    <!-- Partie gauche -->
    <div class="left-section">
        <h1 class="h11">S'INSCRIRE SUR DISCOVER DZ</h1>
        <p><b>Déjà inscrit ?</b> <a href="connecter.php">Rendez-vous sur la page de connexion.</a></p>

        <div class="fb">
           <a href="https://www.facebook.com/" style="text-decoration: none;"><h2><i class="fa-brands fa-facebook-f"></i> - S'inscrire avec Facebook *</h2></a>
        </div>
        <p class="sp">En accédant à Discover DZ avec Facebook, vous acceptez <a href="condition.html">les conditions générales d'utilisation.</a></p>

        <div class="divider"><span>ou</span></div>

        <h3>Inscrivez-vous avec votre email</h3>


        <form class="form-group" method="POST" action="s'inscrire.php">
            <label class="control-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Entrez votre adresse email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <p class="bt-helper-form">
                Cette adresse email sera votre identifiant de connexion et ne sera jamais révélée.
            </p>

            <label class="control-label">Pseudo</label>
            <input type="text" name="pseudo" class="form-control" placeholder="Entrez votre pseudo" required value="<?php echo isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : ''; ?>">
            <p class="bt-helper-form">
                <i class="fa-solid fa-triangle-exclamation"></i> Attention, il ne sera pas possible de le modifier.
            </p>

            <label class="control-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required>
            <p class="bt-helper-form">
                Votre mot de passe sera chiffré et protégé.
            </p>

            <label class="control-label">Confirmation du mot de passe</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirmez votre mot de passe" required>

            <div class="checkbox">
                <label><input type="checkbox" name="terms" required <?php echo isset($_POST['terms']) ? 'checked' : ''; ?>> J'accepte les conditions générales d'utilisation</label>
            </div>
            
            <button type="submit" class="btn">Valider mon inscription</button>
        </form>
    </div>

    <!-- Partie droite -->
    <div class="disc">
        <div class="discc">
            <h4><strong>Discover DZ</strong></h4>
            <p>
                Discover DZ est un site web qui permet de préparer, puis raconter et partager facilement ses plus beaux voyages.
            </p>
            <p>
                En quelques clics, soyez prêts à raconter vos plus belles aventures et à les partager autour de vous !
            </p>
            <p><strong>Bon voyage !</strong></p>
        </div>
        <div class="log">
            <img src="image/logo3.jpg" alt="Discover DZ Logo">
            <h1>DISCOVER DZ</h1>
            <span>Partagez vos aventures</span>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-container">
      <!-- Contact -->
      <div class="footer-column">
          <h3>Contactez-nous</h3>
          <div class="social-icons">
              <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a>
              <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
              <a href="https://www.gmail.com/"><i class="fas fa-envelope"></i></a>
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