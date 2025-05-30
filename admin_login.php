<?php
session_start();

// Connexion à la base de données
require_once('includes/db_connect.php');

// Initialiser les variables
$username = "test@test.com";
$password = "test@test.com";
$error = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    
    // Validation simple
    if (empty($username) || empty($password)) {
        $error = "Veuillez remplir tous les champs";
    } else {
        // Hardcoded credentials for testing
        if ($username === "test@test.com" && $password === "test@test.com") {
            // Authentification réussie
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_id'] = 1; // Example admin ID
            $_SESSION['username'] = $username;
            
            // Redirection vers le tableau de bord admin
            header("Location: admin.php");
            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Discover DZ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styleadmin.css">
    <style>
        /* Styles spécifiques à la page de connexion */
        body {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 400px;
            padding: 2rem;
            text-align: center;
        }
        
        .login-header {
            margin-bottom: 2rem;
        }
        
        .login-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        
        .login-header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--text-light);
        }
        
        .login-form .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .login-form .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .login-form .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }
        
        .login-form .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        .login-form .btn-login {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .login-form .btn-login:hover {
            background-color: var(--hover-color);
        }
        
        .login-form .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .back-to-site {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        
        .back-to-site a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        .back-to-site a:hover {
            text-decoration: underline;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="image/logo3.jpg" alt="Discover DZ Logo">
            <h1>Discover DZ</h1>
            <p>Panneau d'administration</p>
        </div>
        
        <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <i class="fas fa-user form-icon"></i>
                <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock form-icon"></i>
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>
        
        <div class="back-to-site">
            <a href="accueil.php"><i class="fas fa-arrow-left"></i> Retour au site</a>
        </div>
    </div>
</body>
</html>