<?php
/**
 * Modelo de Usuario
 */
class User {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Autenticar usuario
     */
    public function authenticateUser($email, $password) {
        $stmt = $this->db->prepare("
            SELECT id, nombre, email, password, rol, activo, fecha_creacion 
            FROM usuarios 
            WHERE email = ? AND activo = 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último acceso
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Crear nuevo usuario
     */
    public function createUser($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (nombre, email, password, rol, activo, fecha_creacion) 
            VALUES (?, ?, ?, 'usuario', 1, NOW())
        ");
        
        try {
            $stmt->execute([
                $data['nombre'],
                $data['email'],
                $hashedPassword
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Verificar si el email ya existe
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Obtener usuario por ID
     */
    public function getUserById($id) {
        $stmt = $this->db->prepare("
            SELECT id, nombre, email, rol, activo, fecha_creacion, ultimo_acceso 
            FROM usuarios 
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function getAllUsers() {
        $stmt = $this->db->prepare("
            SELECT id, nombre, email, rol, activo, fecha_creacion, ultimo_acceso 
            FROM usuarios 
            ORDER BY nombre
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Actualizar último acceso
     */
    private function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }
    
    /**
     * Actualizar usuario
     */
    public function updateUser($id, $data) {
        $sql = "UPDATE usuarios SET nombre = ?, email = ?, rol = ?, activo = ? WHERE id = ?";
        $params = [$data['nombre'], $data['email'], $data['rol'], $data['activo'], $id];
        
        if (!empty($data['password'])) {
            $sql = "UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol = ?, activo = ? WHERE id = ?";
            $params = [
                $data['nombre'], 
                $data['email'], 
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['rol'], 
                $data['activo'], 
                $id
            ];
        }
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar usuario (desactivar)
     */
    public function deleteUser($id) {
        $stmt = $this->db->prepare("UPDATE usuarios SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>