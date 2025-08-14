<?php
/**
 * Modelo de Producto/Inventario
 */
class Product {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Obtener todos los productos con paginación
     */
    public function getAllProducts($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias_productos c ON p.categoria_id = c.id 
            WHERE p.activo = 1 
            ORDER BY p.nombre 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar productos
     */
    public function searchProducts($search, $limit = 10, $offset = 0) {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias_productos c ON p.categoria_id = c.id 
            WHERE p.activo = 1 
            AND (p.nombre LIKE ? OR p.codigo LIKE ? OR p.descripcion LIKE ?)
            ORDER BY p.nombre 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener productos con bajo stock
     */
    public function getLowStockProducts($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias_productos c ON p.categoria_id = c.id 
            WHERE p.activo = 1 AND p.stock <= p.stock_minimo
            ORDER BY (p.stock - p.stock_minimo) ASC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener total de productos
     */
    public function getTotalCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM productos WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    /**
     * Obtener total de productos con bajo stock
     */
    public function getLowStockCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM productos WHERE activo = 1 AND stock <= stock_minimo");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    /**
     * Obtener total de resultados de búsqueda
     */
    public function getSearchCount($search) {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM productos 
            WHERE activo = 1 
            AND (nombre LIKE ? OR codigo LIKE ? OR descripcion LIKE ?)
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetch()['total'];
    }
    
    /**
     * Obtener producto por ID
     */
    public function getProductById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias_productos c ON p.categoria_id = c.id 
            WHERE p.id = ? AND p.activo = 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Crear nuevo producto
     */
    public function createProduct($data) {
        $stmt = $this->db->prepare("
            INSERT INTO productos (
                codigo, nombre, descripcion, categoria_id, unidad_medida, 
                precio_compra, precio_venta, stock, stock_minimo, stock_maximo, ubicacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        try {
            $stmt->execute([
                $data['codigo'],
                $data['nombre'],
                $data['descripcion'] ?? null,
                $data['categoria_id'],
                $data['unidad_medida'] ?? 'PZA',
                $data['precio_compra'],
                $data['precio_venta'],
                $data['stock'] ?? 0,
                $data['stock_minimo'] ?? 1,
                $data['stock_maximo'] ?? 100,
                $data['ubicacion'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar producto
     */
    public function updateProduct($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE productos SET 
                codigo = ?, nombre = ?, descripcion = ?, categoria_id = ?, unidad_medida = ?, 
                precio_compra = ?, precio_venta = ?, stock_minimo = ?, stock_maximo = ?, ubicacion = ?
            WHERE id = ? AND activo = 1
        ");
        
        try {
            return $stmt->execute([
                $data['codigo'],
                $data['nombre'],
                $data['descripcion'] ?? null,
                $data['categoria_id'],
                $data['unidad_medida'] ?? 'PZA',
                $data['precio_compra'],
                $data['precio_venta'],
                $data['stock_minimo'] ?? 1,
                $data['stock_maximo'] ?? 100,
                $data['ubicacion'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar producto (desactivar)
     */
    public function deleteProduct($id) {
        $stmt = $this->db->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Verificar si el código ya existe
     */
    public function codeExists($code, $excludeId = null) {
        $sql = "SELECT id FROM productos WHERE codigo = ? AND activo = 1";
        $params = [$code];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Obtener categorías
     */
    public function getCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categorias_productos WHERE activo = 1 ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Crear movimiento de inventario
     */
    public function createMovement($data) {
        $this->db->beginTransaction();
        
        try {
            // Insertar el movimiento
            $stmt = $this->db->prepare("
                INSERT INTO movimientos_inventario (
                    producto_id, tipo_movimiento, cantidad, precio_unitario, 
                    referencia, observaciones, usuario_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['producto_id'],
                $data['tipo_movimiento'],
                $data['cantidad'],
                $data['precio_unitario'],
                $data['referencia'],
                $data['observaciones'],
                $data['usuario_id']
            ]);
            
            // Actualizar el stock del producto
            $newStock = $this->calculateNewStock($data['producto_id'], $data['tipo_movimiento'], $data['cantidad']);
            
            $stmt = $this->db->prepare("UPDATE productos SET stock = ? WHERE id = ?");
            $stmt->execute([$newStock, $data['producto_id']]);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Calcular nuevo stock
     */
    private function calculateNewStock($productId, $movementType, $quantity) {
        $stmt = $this->db->prepare("SELECT stock FROM productos WHERE id = ?");
        $stmt->execute([$productId]);
        $currentStock = $stmt->fetch()['stock'];
        
        switch ($movementType) {
            case 'entrada':
                return $currentStock + $quantity;
            case 'salida':
                return max(0, $currentStock - $quantity);
            case 'ajuste':
                return $quantity;
            default:
                return $currentStock;
        }
    }
    
    /**
     * Obtener movimientos de un producto
     */
    public function getProductMovements($productId, $limit = 20) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre as usuario_nombre 
            FROM movimientos_inventario m 
            LEFT JOIN usuarios u ON m.usuario_id = u.id 
            WHERE m.producto_id = ? 
            ORDER BY m.fecha_movimiento DESC 
            LIMIT ?
        ");
        $stmt->execute([$productId, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estadísticas de productos
     */
    public function getProductStats() {
        $stats = [];
        
        // Total de productos activos
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM productos WHERE activo = 1");
        $stmt->execute();
        $stats['total'] = $stmt->fetch()['total'];
        
        // Productos con bajo stock
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM productos WHERE activo = 1 AND stock <= stock_minimo");
        $stmt->execute();
        $stats['bajo_stock'] = $stmt->fetch()['total'];
        
        // Valor total del inventario
        $stmt = $this->db->prepare("SELECT SUM(stock * precio_compra) as valor FROM productos WHERE activo = 1");
        $stmt->execute();
        $stats['valor_inventario'] = $stmt->fetch()['valor'] ?? 0;
        
        return $stats;
    }
}
?>