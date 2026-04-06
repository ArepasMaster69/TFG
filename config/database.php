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
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arrays asociativos limpios
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Seguridad extra contra inyecciones SQL
        ];
        
        return new PDO($dsn, DB_USER, DB_PASS, $options);
        
    } catch (PDOException $error) {
        // En un entorno profesional no mostraríamos el error exacto al usuario, 
        // pero para el desarrollo del TFG local es vital para que Jorge pueda depurar.
        die("Database connection failed. Please check your configuration.");
    }
}