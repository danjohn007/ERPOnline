<?php
/**
 * ERP Online - Sistema de Gestión Empresarial
 * Punto de entrada principal de la aplicación
 */

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constantes del sistema
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Incluir archivos de configuración
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/controllers/BaseController.php';

// Iniciar sesión
session_start();

// Obtener la ruta base (subcarpeta, si aplica)
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Eliminar el basePath de la ruta si existe
if ($basePath && strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}
$path = trim($path, '/');

// Parsear la URL
$segments = empty($path) ? [] : explode('/', $path);
$controller = !empty($segments[0]) ? $segments[0] : 'dashboard';
$action = !empty($segments[1]) ? $segments[1] : 'index';

// Verificar autenticación para páginas protegidas
if ($controller !== 'auth' && !isset($_SESSION['user_id'])) {
    header('Location: ' . ($basePath ? $basePath : '') . '/auth/login');
    exit;
}

// Cargar el controlador apropiado
$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile = APP_PATH . '/controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();
        
        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
        } else {
            // Acción no encontrada
            header('HTTP/1.0 404 Not Found');
            echo "Página no encontrada - Acción: $action";
        }
    } else {
        // Clase de controlador no encontrada
        header('HTTP/1.0 404 Not Found');
        echo "Página no encontrada - Controlador: $controllerClass";
    }
} else {
    // Archivo de controlador no encontrado
    header('HTTP/1.0 404 Not Found');
    echo "Página no encontrada - Archivo: $controllerFile";
}
?>
