<?php
/**
 * Controlador de Clientes - Módulo CRM
 */
require_once APP_PATH . '/models/Customer.php';

class CustomersController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $customerModel = new Customer();
        $search = isset($_GET['search']) ? $this->sanitize($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        if ($search) {
            $customers = $customerModel->searchCustomers($search, $limit, $offset);
            $totalCustomers = $customerModel->getSearchCount($search);
        } else {
            $customers = $customerModel->getAllCustomers($limit, $offset);
            $totalCustomers = $customerModel->getTotalCount();
        }
        
        $totalPages = ceil($totalCustomers / $limit);
        $flash = $this->getFlash();
        
        $this->loadView('customers/index', [
            'customers' => $customers,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCustomers' => $totalCustomers,
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
                $customerModel = new Customer();
                $customerId = $customerModel->createCustomer($data);
                
                if ($customerId) {
                    $this->setFlash('success', 'Cliente creado exitosamente');
                    $this->redirect('/customers');
                } else {
                    $errors['general'] = 'Error al crear el cliente';
                }
            }
            
            $this->loadView('customers/create', ['errors' => $errors, 'data' => $data]);
        } else {
            $this->loadView('customers/create');
        }
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            $this->setFlash('error', 'ID de cliente no válido');
            $this->redirect('/customers');
        }
        
        $customerModel = new Customer();
        $customer = $customerModel->getCustomerById($id);
        
        if (!$customer) {
            $this->setFlash('error', 'Cliente no encontrado');
            $this->redirect('/customers');
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
                if ($customerModel->updateCustomer($id, $data)) {
                    $this->setFlash('success', 'Cliente actualizado exitosamente');
                    $this->redirect('/customers');
                } else {
                    $errors['general'] = 'Error al actualizar el cliente';
                }
            }
            
            $this->loadView('customers/edit', ['errors' => $errors, 'data' => $data, 'customer' => $customer]);
        } else {
            $this->loadView('customers/edit', ['customer' => $customer]);
        }
    }
    
    public function view() {
        $this->requireAuth();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            $this->setFlash('error', 'ID de cliente no válido');
            $this->redirect('/customers');
        }
        
        $customerModel = new Customer();
        $customer = $customerModel->getCustomerById($id);
        
        if (!$customer) {
            $this->setFlash('error', 'Cliente no encontrado');
            $this->redirect('/customers');
        }
        
        $this->loadView('customers/view', ['customer' => $customer]);
    }
    
    public function delete() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            
            if ($id) {
                $customerModel = new Customer();
                if ($customerModel->deleteCustomer($id)) {
                    $this->setFlash('success', 'Cliente eliminado exitosamente');
                } else {
                    $this->setFlash('error', 'Error al eliminar el cliente');
                }
            } else {
                $this->setFlash('error', 'ID de cliente no válido');
            }
        }
        
        $this->redirect('/customers');
    }
}
?>