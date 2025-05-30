# Database Connection System

## Overview
This directory contains the centralized database connection system for the Discover DZ application. The main connection file (`db_connect.php`) establishes a PDO connection to the MySQL database that all pages in the application can use.

## Files
- `db_connect.php`: The main database connection file that establishes a PDO connection to the MySQL database.
- `user_functions.php`: Contains user-related helper functions that use the database connection.

## Usage
To use the database connection in any PHP file, simply include the following line at the beginning of your file:

```php
require_once('includes/db_connect.php');
```

After including this file, you'll have access to the `$conn` variable, which is a PDO connection object that you can use to interact with the database.

## Connection Details
The connection uses the following configuration:
- Host: localhost
- Database: memoire
- Username: root
- Password: (empty by default for XAMPP)
- Charset: utf8mb4

## Error Handling
The connection is configured to throw exceptions when SQL errors occur. Make sure to use try/catch blocks when executing database queries to properly handle any potential errors.

## Example
```php
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
```

## Legacy Support
For backward compatibility with code that uses mysqli instead of PDO, the `connection.php` file in the root directory has been updated to use this centralized connection while still providing mysqli functionality.