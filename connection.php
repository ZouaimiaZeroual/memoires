<?php
/**
 * Fichier de connexion à la base de données
 * Ce fichier est maintenu pour la compatibilité avec le code existant
 * Il utilise maintenant le fichier de connexion centralisé
 */

// Inclure le fichier de connexion centralisé
require_once('includes/db_connect.php');

// Pour la compatibilité avec le code qui utilise mysqli au lieu de PDO
$host = 'localhost';
$user = 'root';
$pass = ''; 
$dbname = 'memoire';

// Créer également une connexion mysqli pour la compatibilité avec le code existant
$mysqli_conn = new mysqli($host, $user, $pass, $dbname);
?>