<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Discover DZ, une plateforme pour raconter et partager ses expériences!</title>
    <link rel="stylesheet" type="text/css" href="styleconnecter.css">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,1,-25"
    />
    <link rel="stylesheet" href="icones/all.css">
</head>
<body>
<?php
ob_start(); // Démarre la mise en tampon de sortie

// Démarrer la session
session_start();

// Si l'utilisateur est déjà connecté, rediriger vers accueil.php
if (isset($_SESSION['user_id'])) {
    header("Location: accueil.php");
    exit();
}

// Connexion à la base de données
require_once('includes/db_connect.php');
// La connexion à la base de données est maintenant disponible via $conn

// Traitement du formulaire
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, email, username, password_hash FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_pseudo'] = $user['username'];
                
                header("Location: accueil.php");
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } catch(PDOException $e) {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            // En développement seulement :
            // $error = "Erreur: " . $e->getMessage();
        }
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
                    <a href="connecter.php" id="destination">DESTINATIONS</a>
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
            <li><a href="creer-carnet.php">ECRIRE SON CARNET DE VOYAGE</a></li> 
            <li><a href="creer-experience.php">ECRIRE SON EXPERIENCE</a></li>
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
        <h1 class="h11">SE CONNECTER SUR DISCOVER DZ</h1>
        <p><b>Pas encore inscrit ?</b> <a href="s'inscrire.php">Créez un compte.</a></p>
           <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                <?php unset($_SESSION['success_message']); // Supprimer le message après l'avoir affiché ?>
            </div>
        <?php endif; ?>

        <div class="fb">
          <a href="https://www.facebook.com/" style="text-decoration: none;"><h2><i class="fa-brands fa-facebook-f"></i>- Se connecter avec Facebook *</h2></a>
        </div>
        <p class="sp">En accédant à Discover DZ avec Facebook, vous acceptez <a href="#">les conditions générales d'utilisation</a>.</p>

        <div class="divider"><span>ou</span></div>

        <h3>Connectez-vous avec votre email</h3>

        <form class="form-group" method="POST" action="connecter.php">
            <input type="email" name="email" class="form-control" placeholder="Entrez votre adresse email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe">
            <button type="submit" class="btn">Se connecter</button>
        </form>
    </div>

    <!-- Partie droite -->
    <div class="disc">
        <div class="discc">
            <h4><strong>Carnets de voyage et expérience</strong></h4>
            <p>
                Connectez-vous sur DiscoverDZ avec vos identifiants choisis lors de votre inscription afin de pouvoir raconter
                et partager vos plus beaux voyages !
            </p>
        </div>
        <div class="log">
            <img src="image/logo3.jpg" alt="Discover DZ Logo" />
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