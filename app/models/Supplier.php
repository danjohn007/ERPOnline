<?php
/**
 * Modelo de Proveedor
 */
class Supplier {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Obtener todos los proveedores con paginación
     */
    public function getAllSuppliers($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT * FROM proveedores 
            WHERE activo = 1 
            ORDER BY nombre 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar proveedores
     */
    public function searchSuppliers($search, $limit = 10, $offset = 0) {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("
            SELECT * FROM proveedores 
            WHERE activo = 1 
            AND (nombre LIKE ? OR email LIKE ? OR rfc LIKE ? OR telefono LIKE ?)
            ORDER BY nombre 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener total de proveedores
     */
    public function getTotalCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM proveedores WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    /**
     * Obtener total de resultados de búsqueda
     */
    public function getSearchCount($search) {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM proveedores 
            WHERE activo = 1 
            AND (nombre LIKE ? OR email LIKE ? OR rfc LIKE ? OR telefono LIKE ?)
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetch()['total'];
    }
    
    /**
     * Obtener proveedor por ID
     */
    public function getSupplierById($id) {
        $stmt = $this->db->prepare("SELECT * FROM proveedores WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Crear nuevo proveedor
     */
    public function createSupplier($data) {
        $stmt = $this->db->prepare("
            INSERT INTO proveedores (
                nombre, rfc, email, telefono, direccion, ciudad, estado, 
                codigo_postal, pais, contacto_principal, dias_pago, cuenta_bancaria, banco
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
                $data['dias_pago'] ?? 30,
                $data['cuenta_bancaria'] ?? null,
                $data['banco'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar proveedor
     */
    public function updateSupplier($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE proveedores SET 
                nombre = ?, rfc = ?, email = ?, telefono = ?, direccion = ?, 
                ciudad = ?, estado = ?, codigo_postal = ?, pais = ?, 
                contacto_principal = ?, dias_pago = ?, cuenta_bancaria = ?, banco = ?
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
                $data['dias_pago'] ?? 30,
                $data['cuenta_bancaria'] ?? null,
                $data['banco'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar proveedor (desactivar)
     */
    public function deleteSupplier($id) {
        $stmt = $this->db->prepare("UPDATE proveedores SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Verificar si el RFC ya existe
     */
    public function rfcExists($rfc, $excludeId = null) {
        if (empty($rfc)) return false;
        
        $sql = "SELECT id FROM proveedores WHERE rfc = ? AND activo = 1";
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
     * Obtener estadísticas de proveedores
     */
    public function getSupplierStats() {
        $stats = [];
        
        // Total de proveedores activos
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM proveedores WHERE activo = 1");
        $stmt->execute();
        $stats['total'] = $stmt->fetch()['total'];
        
        // Proveedores nuevos este mes
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM proveedores 
            WHERE activo = 1 AND MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())
        ");
        $stmt->execute();
        $stats['nuevos_mes'] = $stmt->fetch()['total'];
        
        return $stats;
    }
}
?>