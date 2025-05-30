<?php
// Démarrage de la session
session_start();

// Connexion à la base de données
require_once('includes/db_connect.php');

// Pour compatibilité avec le code existant qui utilise $pdo
$pdo = $conn;

// Traitement du formulaire
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des données
    $type_etablissement = trim($_POST['type_etablissement'] ?? '');
    $wilaya = trim($_POST['wilaya'] ?? '');
    $nom_etablissement = trim($_POST['nom_etablissement'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pseudo = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';
    $terms = isset($_POST['terms']) ? true : false;

    // Validation des champs
    // Validation simplifiée sans champs utilisateur
    if (empty($type_etablissement)) $errors['type_etablissement'] = "Le type d'établissement est requis";
    if (empty($wilaya)) $errors['wilaya'] = "La wilaya est requise";
    if (empty($nom_etablissement)) $errors['nom_etablissement'] = "Le nom de l'établissement est requis";
    if (empty($nom)) $errors['nom'] = "Le nom est requis";
    if (empty($prenom)) $errors['prenom'] = "Le prénom est requis";
    if (empty($telephone)) $errors['telephone'] = "Le téléphone est requis";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Email invalide";

    // Vérification email unique
    $stmt = $pdo->prepare("SELECT id FROM establishments WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) $errors['email'] = "Cet email est déjà utilisé";

    // Validate and assign type
    $type_valide = null;
    if (in_array($type_etablissement, ['Hôtel', 'Appartement', 'Clinique'])) {
        $type_valide = $type_etablissement;
    } else {
        $errors['type_etablissement'] = "Type d'établissement invalide";
    }

    // Ensure $type_valide is not null before proceeding
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Insertion directe dans establishments
            $stmt = $pdo->prepare("INSERT INTO establishments 
                (nom_etablissement, nom, prenom, telephone, email, type, wilaya, approval_status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");

            $stmt->execute([
                $nom_etablissement,
                $nom,
                $prenom,
                $telephone,
                $email,
                $type_valide, // Ensure this is not null
                $wilaya
            ]);

            $pdo->commit();
            header("Location: connecter.php");
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<div style='color:red; padding:20px; background:#ffeeee; border:1px solid red;'>Erreur : " 
                . htmlspecialchars($e->getMessage()) . "</div>";
            die();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajouter_votre_etblsmt_inscription</title>
    <link rel="stylesheet" type="text/css" href="ajouter_votre_etblsmt_inscription.css">
</head>
<body> 
    <header>
        <div class="mydiv">
          <a href="accueil.php"><img src="image/logo3.jpg" alt="Discover DZ Logo"></a>
        </div>
        <nav class="navv">
            <ul class="navbar">
               <li><a href="explore.php" >EXPLORE</a></li> 
               <li> <nav class="menu-container">
                <ul class="menu">
                    <li class="menu-item">
                        <a href="wilaya.html" id="destination">DESTINATIONS</a>
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
               <li><a href="ajouter_votre_etablissement.php" >AJOUTER VOTRE ETABLISSEMENT</a></li>
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
        <div class="img"></div>
        
        <div class="content-row">
          <div class="card inscription-card">
            <h2>DISCOVER DZ</h2>
            <div class="card-header">
              <h3>Créez votre compte partenaire</h3>
              <p>et gérez votre établissement.</p>
            </div>
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif;?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <p>Votre compte partenaire a été créé avec succès. Votre établissement est en attente de validation.</p>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
              <div class="form-section">
                <h4>Informations établissement</h4>
                
                <div class="form-row">
                <div class="form-group">
                  <label>Type d'établissement</label>
                  <select name="type_etablissement" class="select" required>
                    <option value="">Sélectionnez un type</option>
                    <option value="Hôtel" <?= isset($_POST['type_etablissement']) && $_POST['type_etablissement'] === 'Hôtel' ? 'selected' : '' ?>>Hôtel</option>
                    <option value="Appartement" <?= isset($_POST['type_etablissement']) && $_POST['type_etablissement'] === 'Appartement' ? 'selected' : '' ?>>Appartement</option>
                    <option value="Clinique" <?= isset($_POST['type_etablissement']) && $_POST['type_etablissement'] === 'Clinique' ? 'selected' : '' ?>>Clinique</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label>Wilaya</label>
                  <select name="wilaya" class="select" required>
    <option value="">Sélectionnez une wilaya</option>
    <option value="1 - Adrar" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '1 - Adrar' ? 'selected' : '' ?>>1 - Adrar</option>
    <option value="2 - Chlef" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '2 - Chlef' ? 'selected' : '' ?>>2 - Chlef</option>
    <option value="3 - Laghouat" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '3 - Laghouat' ? 'selected' : '' ?>>3 - Laghouat</option>
    <option value="4 - Oum El Bouaghi" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '4 - Oum El Bouaghi' ? 'selected' : '' ?>>4 - Oum El Bouaghi</option>
    <option value="5 - Batna" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '5 - Batna' ? 'selected' : '' ?>>5 - Batna</option>
    <option value="6 - Béjaïa" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '6 - Béjaïa' ? 'selected' : '' ?>>6 - Béjaïa</option>
    <option value="7 - Biskra" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '7 - Biskra' ? 'selected' : '' ?>>7 - Biskra</option>
    <option value="8 - Béchar" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '8 - Béchar' ? 'selected' : '' ?>>8 - Béchar</option>
    <option value="9 - Blida" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '9 - Blida' ? 'selected' : '' ?>>9 - Blida</option>
    <option value="10 - Bouira" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '10 - Bouira' ? 'selected' : '' ?>>10 - Bouira</option>
    <option value="11 - Tamanrasset" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '11 - Tamanrasset' ? 'selected' : '' ?>>11 - Tamanrasset</option>
    <option value="12 - Tébessa" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '12 - Tébessa' ? 'selected' : '' ?>>12 - Tébessa</option>
    <option value="13 - Tlemcen" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '13 - Tlemcen' ? 'selected' : '' ?>>13 - Tlemcen</option>
    <option value="14 - Tiaret" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '14 - Tiaret' ? 'selected' : '' ?>>14 - Tiaret</option>
    <option value="15 - Tizi Ouzou" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '15 - Tizi Ouzou' ? 'selected' : '' ?>>15 - Tizi Ouzou</option>
    <option value="16 - Alger" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '16 - Alger' ? 'selected' : '' ?>>16 - Alger</option>
    <option value="17 - Djelfa" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '17 - Djelfa' ? 'selected' : '' ?>>17 - Djelfa</option>
    <option value="18 - Jijel" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '18 - Jijel' ? 'selected' : '' ?>>18 - Jijel</option>
    <option value="19 - Sétif" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '19 - Sétif' ? 'selected' : '' ?>>19 - Sétif</option>
    <option value="20 - Saïda" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '20 - Saïda' ? 'selected' : '' ?>>20 - Saïda</option>
    <option value="21 - Skikda" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '21 - Skikda' ? 'selected' : '' ?>>21 - Skikda</option>
    <option value="22 - Sidi Bel Abbès" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '22 - Sidi Bel Abbès' ? 'selected' : '' ?>>22 - Sidi Bel Abbès</option>
    <option value="23 - Annaba" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '23 - Annaba' ? 'selected' : '' ?>>23 - Annaba</option>
    <option value="24 - Guelma" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '24 - Guelma' ? 'selected' : '' ?>>24 - Guelma</option>
    <option value="25 - Constantine" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '25 - Constantine' ? 'selected' : '' ?>>25 - Constantine</option>
    <option value="26 - Médéa" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '26 - Médéa' ? 'selected' : '' ?>>26 - Médéa</option>
    <option value="27 - Mostaganem" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '27 - Mostaganem' ? 'selected' : '' ?>>27 - Mostaganem</option>
    <option value="28 - M'Sila" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '28 - M\'Sila' ? 'selected' : '' ?>>28 - M'Sila</option>
    <option value="29 - Mascara" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '29 - Mascara' ? 'selected' : '' ?>>29 - Mascara</option>
    <option value="30 - Ouargla" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '30 - Ouargla' ? 'selected' : '' ?>>30 - Ouargla</option>
    <option value="31 - Oran" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '31 - Oran' ? 'selected' : '' ?>>31 - Oran</option>
    <option value="32 - El Bayadh" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '32 - El Bayadh' ? 'selected' : '' ?>>32 - El Bayadh</option>
    <option value="33 - Illizi" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '33 - Illizi' ? 'selected' : '' ?>>33 - Illizi</option>
    <option value="34 - Bordj Bou Arreridj" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '34 - Bordj Bou Arreridj' ? 'selected' : '' ?>>34 - Bordj Bou Arreridj</option>
    <option value="35 - Boumerdès" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '35 - Boumerdès' ? 'selected' : '' ?>>35 - Boumerdès</option>
    <option value="36 - El Tarf" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '36 - El Tarf' ? 'selected' : '' ?>>36 - El Tarf</option>
    <option value="37 - Tindouf" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '37 - Tindouf' ? 'selected' : '' ?>>37 - Tindouf</option>
    <option value="38 - Tissemsilt" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '38 - Tissemsilt' ? 'selected' : '' ?>>38 - Tissemsilt</option>
    <option value="39 - El Oued" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '39 - El Oued' ? 'selected' : '' ?>>39 - El Oued</option>
    <option value="40 - Khenchela" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '40 - Khenchela' ? 'selected' : '' ?>>40 - Khenchela</option>
    <option value="41 - Souk Ahras" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '41 - Souk Ahras' ? 'selected' : '' ?>>41 - Souk Ahras</option>
    <option value="42 - Tipaza" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '42 - Tipaza' ? 'selected' : '' ?>>42 - Tipaza</option>
    <option value="43 - Mila" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '43 - Mila' ? 'selected' : '' ?>>43 - Mila</option>
    <option value="44 - Aïn Defla" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '44 - Aïn Defla' ? 'selected' : '' ?>>44 - Aïn Defla</option>
    <option value="45 - Naâma" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '45 - Naâma' ? 'selected' : '' ?>>45 - Naâma</option>
    <option value="46 - Aïn Témouchent" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '46 - Aïn Témouchent' ? 'selected' : '' ?>>46 - Aïn Témouchent</option>
    <option value="47 - Ghardaïa" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '47 - Ghardaïa' ? 'selected' : '' ?>>47 - Ghardaïa</option>
    <option value="48 - Relizane" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '48 - Relizane' ? 'selected' : '' ?>>48 - Relizane</option>
    <option value="49 - El M'Ghair" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '49 - El M\'Ghair' ? 'selected' : '' ?>>49 - El M'Ghair</option>
    <option value="50 - El Menia" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '50 - El Menia' ? 'selected' : '' ?>>50 - El Menia</option>
    <option value="51 - Ouled Djellal" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '51 - Ouled Djellal' ? 'selected' : '' ?>>51 - Ouled Djellal</option>
    <option value="52 - Bordj Badji Mokhtar" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '52 - Bordj Badji Mokhtar' ? 'selected' : '' ?>>52 - Bordj Badji Mokhtar</option>
    <option value="53 - Béni Abbès" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '53 - Béni Abbès' ? 'selected' : '' ?>>53 - Béni Abbès</option>
    <option value="54 - Timimoun" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '54 - Timimoun' ? 'selected' : '' ?>>54 - Timimoun</option>
    <option value="55 - Touggourt" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '55 - Touggourt' ? 'selected' : '' ?>>55 - Touggourt</option>
    <option value="56 - Djanet" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '56 - Djanet' ? 'selected' : '' ?>>56 - Djanet</option>
    <option value="57 - In Salah" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '57 - In Salah' ? 'selected' : '' ?>>57 - In Salah</option>
    <option value="58 - In Guezzam" <?= isset($_POST['wilaya']) && $_POST['wilaya'] === '58 - In Guezzam' ? 'selected' : '' ?>>58 - In Guezzam</option>
</select>
                </div>
                </div>

                <div class="form-group">
                  <label>Nom de l'établissement</label>
                  <input type="text" name="nom_etablissement" class="input" value="<?= htmlspecialchars($_POST['nom_etablissement'] ?? '') ?>" required>
                </div>
              </div>
              
              <div class="form-section">
                <h4>Informations du compte</h4>
                
                <div class="form-row">
                  <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="input" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="input" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group">
                    <label>Numéro de téléphone</label>
                    <input type="tel" name="telephone" class="input" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" class="input" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                  </div>
                </div>
                
                <div class="form-row">
                <div class="form-group">
                  <label>Nom d'utilisateur</label>
                  <input type="text" name="pseudo" class="input" value="<?= htmlspecialchars($_POST['pseudo'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                  <label>Mot de passe</label>
                  <input type="password" name="password" class="input" required>
                </div>
              </div>
            </div>
              <div class="checkbox-container">
                <input type="checkbox" id="terms" name="terms" required <?= isset($_POST['terms']) ? 'checked' : '' ?>>
                <label for="terms">J'ai lu et j'accepte les conditions du contrat</label>
              </div>
              
              <button type="submit" class="start-btn" id="submit-btn">CRÉER MON COMPTE</button>
              
              <p class="login-link">Déjà partenaire ? <a href="connecter.php">Se connecter</a></p>
            </form>
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
        
        <script>
          // Script pour gérer la soumission du formulaire
          document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submit-btn');
            
            if (form && submitBtn) {
              form.addEventListener('submit', function() {
                // Vérifier si le formulaire est valide
                if (form.checkValidity()) {
                  // Changer le texte du bouton et le désactiver
                  submitBtn.innerHTML = 'TRAITEMENT EN COURS...';
                  submitBtn.disabled = true;
                  submitBtn.style.backgroundColor = '#718096';
                  
                  // Laisser le formulaire se soumettre
                  return true;
                }
              });
            }
          });
        </script>
    
      </body>
      </html>