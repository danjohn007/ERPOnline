<?php
/**
 * Controlador de autenticación
 */
require_once APP_PATH . '/models/User.php';

class AuthController extends BaseController {
    
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            $password = $_POST['password'];
            
            $errors = $this->validate($_POST, [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);
            
            if (empty($errors)) {
                $userModel = new User();
                $user = $userModel->authenticateUser($email, $password);
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['rol'];
                    
                    $this->setFlash('success', 'Bienvenido al sistema ERP Online');
                    $this->redirect('/dashboard');
                } else {
                    $errors['login'] = 'Credenciales incorrectas';
                }
            }
            
            $this->loadView('auth/login', ['errors' => $errors]);
        } else {
            $this->loadView('auth/login');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/auth/login');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitize($_POST);
            
            $errors = $this->validate($data, [
                'nombre' => 'required|min:2|max:100',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'password_confirm' => 'required'
            ]);
            
            if ($data['password'] !== $data['password_confirm']) {
                $errors['password_confirm'] = 'Las contraseñas no coinciden';
            }
            
            if (empty($errors)) {
                $userModel = new User();
                
                // Verificar si el email ya existe
                if ($userModel->emailExists($data['email'])) {
                    $errors['email'] = 'Este email ya está registrado';
                } else {
                    $userId = $userModel->createUser($data);
                    if ($userId) {
                        $this->setFlash('success', 'Usuario registrado exitosamente. Por favor inicie sesión.');
                        $this->redirect('/auth/login');
                    } else {
                        $errors['general'] = 'Error al crear el usuario';
                    }
                }
            }
            
            $this->loadView('auth/register', ['errors' => $errors, 'data' => $data]);
        } else {
            $this->loadView('auth/register');
        }
    }
}
?>