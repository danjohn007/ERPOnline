<?php
/**
 * Configuración de la base de datos
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'ejercito_erp');
define('DB_USER', 'ejercito_erp');
define('DB_PASS', 'Danjohn007');
define('DB_CHARSET', 'utf8mb4');

/**
 * Clase para manejo de conexión a la base de datos
 */
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {}
}

/**
 * Función helper para obtener conexión a la base de datos
 */
function getDB() {
    return Database::getInstance()->getConnection();
}
?>