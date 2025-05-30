<?php
/**
 * Database Connection File
 * 
 * This file establishes a connection to the memoire database
 * and should be included in all pages that need database access.
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Empty password for XAMPP default
define('DB_NAME', 'memoire');

// Establish database connection
try {
    $conn = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Connection established successfully
    // $conn is now available for use in the including file
    
} catch(PDOException $e) {
    // Handle connection error
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}