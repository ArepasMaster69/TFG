<?php
/**
 * Database configuration and connection setup.
 * Uses PDO for secure database interactions to prevent SQL injection.
 */

// Constantes de configuración (Cero valores mágicos)
// Jorge deberá modificar estos valores si la base de datos tiene contraseña en su XAMPP
const DB_HOST = 'localhost';
const DB_NAME = 'tfg_eventos';
const DB_USER = 'root'; 
const DB_PASS = '';     

/**
 * Retrieves a secure PDO database connection.
 * * @returns {PDO} The active database connection instance.
 */
function getDatabaseConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
            PDO::ATTR_EMULATE_PREPARES   => false,                  
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        initializeDatabaseSchema($pdo);
        return $pdo;
        
    } catch (PDOException $error) {
        
        die("Database connection failed. Please check your configuration.");
    }
}

function initializeDatabaseSchema(PDO $db) {
    $db->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(200) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL DEFAULT 'usuario',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $db->exec(
        "CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            venue VARCHAR(255) NOT NULL,
            event_date DATETIME NOT NULL,
            total_seats INT NOT NULL,
            available_seats INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $db->exec(
        "CREATE TABLE IF NOT EXISTS reservations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            event_id INT NOT NULL,
            reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_reservation (user_id, event_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}