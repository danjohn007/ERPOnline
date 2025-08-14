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

// Calcular la ruta base de la aplicación
$scriptName = $_SERVER['SCRIPT_NAME'];
$requestUri = $_SERVER['REQUEST_URI'];

// Obtener el directorio base donde está instalada la aplicación
$basePath = dirname($scriptName);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}

// Definir la ruta base como constante global
define('BASE_PATH', $basePath);

// Función helper global para generar URLs
function url($path) {
    return BASE_PATH . $path;
}

// Obtener la ruta solicitada
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Remover la ruta base de la ruta solicitada
if ($basePath && strpos($requestPath, $basePath) === 0) {
    $requestPath = substr($requestPath, strlen($basePath));
}

// Limpiar y parsear la ruta
$path = trim($requestPath, '/');
$segments = empty($path) ? [] : explode('/', $path);
$controller = !empty($segments[0]) ? $segments[0] : 'dashboard';
$action = !empty($segments[1]) ? $segments[1] : 'index';

// Verificar autenticación para páginas protegidas
if ($controller !== 'auth' && !isset($_SESSION['user_id'])) {
    $loginUrl = BASE_PATH . '/auth/login';
    header('Location: ' . $loginUrl);
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