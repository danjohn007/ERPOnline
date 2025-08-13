<?php
/**
 * Modelo de Cliente
 */
class Customer {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Obtener todos los clientes con paginación
     */
    public function getAllCustomers($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT * FROM clientes 
            WHERE activo = 1 
            ORDER BY nombre 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar clientes
     */
    public function searchCustomers($search, $limit = 10, $offset = 0) {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("
            SELECT * FROM clientes 
            WHERE activo = 1 
            AND (nombre LIKE ? OR email LIKE ? OR rfc LIKE ? OR telefono LIKE ?)
            ORDER BY nombre 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener total de clientes
     */
    public function getTotalCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM clientes WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    /**
     * Obtener total de resultados de búsqueda
     */
    public function getSearchCount($search) {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM clientes 
            WHERE activo = 1 
            AND (nombre LIKE ? OR email LIKE ? OR rfc LIKE ? OR telefono LIKE ?)
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetch()['total'];
    }
    
    /**
     * Obtener cliente por ID
     */
    public function getCustomerById($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Crear nuevo cliente
     */
    public function createCustomer($data) {
        $stmt = $this->db->prepare("
            INSERT INTO clientes (
                nombre, rfc, email, telefono, direccion, ciudad, estado, 
                codigo_postal, pais, contacto_principal, limite_credito, dias_credito
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        try {
            $stmt->execute([
                $data['nombre'],
                $data['rfc'] ?? null,
                $data['email'] ?? null,
                $data['telefono'] ?? null,
                $data['direccion'] ?? null,
                $data['ciudad'] ?? null,
                $data['estado'] ?? null,
                $data['codigo_postal'] ?? null,
                $data['pais'] ?? 'México',
                $data['contacto_principal'] ?? null,
                $data['limite_credito'] ?? 0.00,
                $data['dias_credito'] ?? 0
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar cliente
     */
    public function updateCustomer($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE clientes SET 
                nombre = ?, rfc = ?, email = ?, telefono = ?, direccion = ?, 
                ciudad = ?, estado = ?, codigo_postal = ?, pais = ?, 
                contacto_principal = ?, limite_credito = ?, dias_credito = ?
            WHERE id = ? AND activo = 1
        ");
        
        try {
            return $stmt->execute([
                $data['nombre'],
                $data['rfc'] ?? null,
                $data['email'] ?? null,
                $data['telefono'] ?? null,
                $data['direccion'] ?? null,
                $data['ciudad'] ?? null,
                $data['estado'] ?? null,
                $data['codigo_postal'] ?? null,
                $data['pais'] ?? 'México',
                $data['contacto_principal'] ?? null,
                $data['limite_credito'] ?? 0.00,
                $data['dias_credito'] ?? 0,
                $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar cliente (desactivar)
     */
    public function deleteCustomer($id) {
        $stmt = $this->db->prepare("UPDATE clientes SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Verificar si el RFC ya existe
     */
    public function rfcExists($rfc, $excludeId = null) {
        if (empty($rfc)) return false;
        
        $sql = "SELECT id FROM clientes WHERE rfc = ? AND activo = 1";
        $params = [$rfc];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Obtener estadísticas de clientes
     */
    public function getCustomerStats() {
        $stats = [];
        
        // Total de clientes activos
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM clientes WHERE activo = 1");
        $stmt->execute();
        $stats['total'] = $stmt->fetch()['total'];
        
        // Clientes nuevos este mes
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM clientes 
            WHERE activo = 1 AND MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())
        ");
        $stmt->execute();
        $stats['nuevos_mes'] = $stmt->fetch()['total'];
        
        return $stats;
    }
}
?>