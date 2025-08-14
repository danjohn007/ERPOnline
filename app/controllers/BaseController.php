<?php
/**
 * Controlador base con funcionalidades comunes
 */
class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Cargar una vista
     */
    protected function loadView($view, $data = []) {
        // Extraer variables para la vista
        extract($data);
        
        // Cargar la vista
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("Vista no encontrada: $view");
        }
    }
    
    /**
     * Redirigir a una URL
     */
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    /**
     * Validar datos de entrada
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = isset($data[$field]) ? trim($data[$field]) : '';
            
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "El campo $field es requerido";
                continue;
            }
            
            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "El campo $field debe ser un email válido";
            }
            
            if (preg_match('/min:(\d+)/', $rule, $matches) && strlen($value) < $matches[1]) {
                $errors[$field] = "El campo $field debe tener al menos {$matches[1]} caracteres";
            }
            
            if (preg_match('/max:(\d+)/', $rule, $matches) && strlen($value) > $matches[1]) {
                $errors[$field] = "El campo $field no puede tener más de {$matches[1]} caracteres";
            }
        }
        
        return $errors;
    }
    
    /**
     * Sanitizar datos de entrada
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Verificar autenticación
     */
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
        }
    }
    
    /**
     * Obtener usuario actual
     */
    protected function getCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        }
        return null;
    }
    
    /**
     * Establecer mensaje flash
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Obtener mensajes flash
     */
    protected function getFlash() {
        $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
        unset($_SESSION['flash']);
        return $flash;
    }
}
?>