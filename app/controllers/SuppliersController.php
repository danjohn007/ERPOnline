<?php
/**
 * Controlador de Proveedores
 */
require_once APP_PATH . '/models/Supplier.php';

class SuppliersController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $supplierModel = new Supplier();
        $search = isset($_GET['search']) ? $this->sanitize($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        if ($search) {
            $suppliers = $supplierModel->searchSuppliers($search, $limit, $offset);
            $totalSuppliers = $supplierModel->getSearchCount($search);
        } else {
            $suppliers = $supplierModel->getAllSuppliers($limit, $offset);
            $totalSuppliers = $supplierModel->getTotalCount();
        }
        
        $totalPages = ceil($totalSuppliers / $limit);
        $flash = $this->getFlash();
        
        $this->loadView('suppliers/index', [
            'suppliers' => $suppliers,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalSuppliers' => $totalSuppliers,
            'flash' => $flash
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitize($_POST);
            
            $errors = $this->validate($data, [
                'nombre' => 'required|min:2|max:150',
                'email' => 'email',
                'telefono' => 'max:20',
                'rfc' => 'max:13'
            ]);
            
            if (empty($errors)) {
                $supplierModel = new Supplier();
                $supplierId = $supplierModel->createSupplier($data);
                
                if ($supplierId) {
                    $this->setFlash('success', 'Proveedor creado exitosamente');
                    $this->redirect('/suppliers');
                } else {
                    $errors['general'] = 'Error al crear el proveedor';
                }
            }
            
            $this->loadView('suppliers/create', ['errors' => $errors, 'data' => $data]);
        } else {
            $this->loadView('suppliers/create');
        }
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            $this->setFlash('error', 'ID de proveedor no válido');
            $this->redirect('/suppliers');
        }
        
        $supplierModel = new Supplier();
        $supplier = $supplierModel->getSupplierById($id);
        
        if (!$supplier) {
            $this->setFlash('error', 'Proveedor no encontrado');
            $this->redirect('/suppliers');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitize($_POST);
            
            $errors = $this->validate($data, [
                'nombre' => 'required|min:2|max:150',
                'email' => 'email',
                'telefono' => 'max:20',
                'rfc' => 'max:13'
            ]);
            
            if (empty($errors)) {
                if ($supplierModel->updateSupplier($id, $data)) {
                    $this->setFlash('success', 'Proveedor actualizado exitosamente');
                    $this->redirect('/suppliers');
                } else {
                    $errors['general'] = 'Error al actualizar el proveedor';
                }
            }
            
            $this->loadView('suppliers/edit', ['errors' => $errors, 'data' => $data, 'supplier' => $supplier]);
        } else {
            $this->loadView('suppliers/edit', ['supplier' => $supplier]);
        }
    }
    
    public function view() {
        $this->requireAuth();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            $this->setFlash('error', 'ID de proveedor no válido');
            $this->redirect('/suppliers');
        }
        
        $supplierModel = new Supplier();
        $supplier = $supplierModel->getSupplierById($id);
        
        if (!$supplier) {
            $this->setFlash('error', 'Proveedor no encontrado');
            $this->redirect('/suppliers');
        }
        
        $this->loadView('suppliers/view', ['supplier' => $supplier]);
    }
    
    public function delete() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            
            if ($id) {
                $supplierModel = new Supplier();
                if ($supplierModel->deleteSupplier($id)) {
                    $this->setFlash('success', 'Proveedor eliminado exitosamente');
                } else {
                    $this->setFlash('error', 'Error al eliminar el proveedor');
                }
            } else {
                $this->setFlash('error', 'ID de proveedor no válido');
            }
        }
        
        $this->redirect('/suppliers');
    }
}
?>