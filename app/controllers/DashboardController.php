<?php
/**
 * Controlador del Dashboard principal
 */
class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Obtener estadísticas para el dashboard
        $stats = $this->getDashboardStats();
        $user = $this->getCurrentUser();
        $flash = $this->getFlash();
        
        $this->loadView('dashboard/index', [
            'stats' => $stats,
            'user' => $user,
            'flash' => $flash
        ]);
    }
    
    /**
     * Obtener estadísticas para el dashboard
     */
    private function getDashboardStats() {
        $stats = [];
        
        try {
            // Total de clientes
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM clientes WHERE activo = 1");
            $stmt->execute();
            $stats['clientes'] = $stmt->fetch()['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['clientes'] = 0;
        }
        
        try {
            // Total de proveedores
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM proveedores WHERE activo = 1");
            $stmt->execute();
            $stats['proveedores'] = $stmt->fetch()['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['proveedores'] = 0;
        }
        
        try {
            // Total de productos en inventario
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM productos WHERE activo = 1");
            $stmt->execute();
            $stats['productos'] = $stmt->fetch()['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['productos'] = 0;
        }
        
        try {
            // Total de usuarios
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE activo = 1");
            $stmt->execute();
            $stats['usuarios'] = $stmt->fetch()['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['usuarios'] = 0;
        }
        
        try {
            // Productos con bajo stock (menos de 10 unidades)
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM productos WHERE stock < 10 AND activo = 1");
            $stmt->execute();
            $stats['bajo_stock'] = $stmt->fetch()['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['bajo_stock'] = 0;
        }
        
        return $stats;
    }
}
?>