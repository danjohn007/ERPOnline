<?php
/**
 * Controlador de Almacén/Inventario
 */
require_once APP_PATH . '/models/Product.php';

class WarehouseController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $productModel = new Product();
        $search = isset($_GET['search']) ? $this->sanitize($_GET['search']) : '';
        $filter = isset($_GET['filter']) ? $this->sanitize($_GET['filter']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Aplicar filtros
        if ($filter === 'low_stock') {
            $products = $productModel->getLowStockProducts($limit, $offset);
            $totalProducts = $productModel->getLowStockCount();
        } elseif ($search) {
            $products = $productModel->searchProducts($search, $limit, $offset);
            $totalProducts = $productModel->getSearchCount($search);
        } else {
            $products = $productModel->getAllProducts($limit, $offset);
            $totalProducts = $productModel->getTotalCount();
        }
        
        $totalPages = ceil($totalProducts / $limit);
        $categories = $productModel->getCategories();
        $flash = $this->getFlash();
        
        $this->loadView('warehouse/index', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'filter' => $filter,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'flash' => $flash
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $productModel = new Product();
        $categories = $productModel->getCategories();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitize($_POST);
            
            $errors = $this->validate($data, [
                'codigo' => 'required|min:1|max:50',
                'nombre' => 'required|min:2|max:150',
                'categoria_id' => 'required',
                'precio_compra' => 'required',
                'precio_venta' => 'required'
            ]);
            
            // Verificar si el código ya existe
            if (empty($errors) && $productModel->codeExists($data['codigo'])) {
                $errors['codigo'] = 'Este código ya existe';
            }
            
            if (empty($errors)) {
                $productId = $productModel->createProduct($data);
                
                if ($productId) {
                    $this->setFlash('success', 'Producto creado exitosamente');
                    $this->redirect('/warehouse');
                } else {
                    $errors['general'] = 'Error al crear el producto';
                }
            }
            
            $this->loadView('warehouse/create', ['errors' => $errors, 'data' => $data, 'categories' => $categories]);
        } else {
            $this->loadView('warehouse/create', ['categories' => $categories]);
        }
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            $this->setFlash('error', 'ID de producto no válido');
            $this->redirect('/warehouse');
        }
        
        $productModel = new Product();
        $product = $productModel->getProductById($id);
        $categories = $productModel->getCategories();
        
        if (!$product) {
            $this->setFlash('error', 'Producto no encontrado');
            $this->redirect('/warehouse');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitize($_POST);
            
            $errors = $this->validate($data, [
                'codigo' => 'required|min:1|max:50',
                'nombre' => 'required|min:2|max:150',
                'categoria_id' => 'required',
                'precio_compra' => 'required',
                'precio_venta' => 'required'
            ]);
            
            // Verificar si el código ya existe (excluyendo el producto actual)
            if (empty($errors) && $productModel->codeExists($data['codigo'], $id)) {
                $errors['codigo'] = 'Este código ya existe';
            }
            
            if (empty($errors)) {
                if ($productModel->updateProduct($id, $data)) {
                    $this->setFlash('success', 'Producto actualizado exitosamente');
                    $this->redirect('/warehouse');
                } else {
                    $errors['general'] = 'Error al actualizar el producto';
                }
            }
            
            $this->loadView('warehouse/edit', ['errors' => $errors, 'data' => $data, 'product' => $product, 'categories' => $categories]);
        } else {
            $this->loadView('warehouse/edit', ['product' => $product, 'categories' => $categories]);
        }
    }
    
    public function movement() {
        $this->requireAuth();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            $this->setFlash('error', 'ID de producto no válido');
            $this->redirect('/warehouse');
        }
        
        $productModel = new Product();
        $product = $productModel->getProductById($id);
        
        if (!$product) {
            $this->setFlash('error', 'Producto no encontrado');
            $this->redirect('/warehouse');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitize($_POST);
            $user = $this->getCurrentUser();
            
            $errors = $this->validate($data, [
                'tipo_movimiento' => 'required',
                'cantidad' => 'required',
                'precio_unitario' => 'required'
            ]);
            
            if ($data['cantidad'] <= 0) {
                $errors['cantidad'] = 'La cantidad debe ser mayor a 0';
            }
            
            if ($data['tipo_movimiento'] === 'salida' && $data['cantidad'] > $product['stock']) {
                $errors['cantidad'] = 'No hay suficiente stock disponible';
            }
            
            if (empty($errors)) {
                $movementData = [
                    'producto_id' => $id,
                    'tipo_movimiento' => $data['tipo_movimiento'],
                    'cantidad' => $data['cantidad'],
                    'precio_unitario' => $data['precio_unitario'],
                    'referencia' => $data['referencia'] ?? null,
                    'observaciones' => $data['observaciones'] ?? null,
                    'usuario_id' => $user['id']
                ];
                
                if ($productModel->createMovement($movementData)) {
                    $this->setFlash('success', 'Movimiento registrado exitosamente');
                    $this->redirect('/warehouse');
                } else {
                    $errors['general'] = 'Error al registrar el movimiento';
                }
            }
            
            $this->loadView('warehouse/movement', ['errors' => $errors, 'data' => $data, 'product' => $product]);
        } else {
            $this->loadView('warehouse/movement', ['product' => $product]);
        }
    }
    
    public function view() {
        $this->requireAuth();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            $this->setFlash('error', 'ID de producto no válido');
            $this->redirect('/warehouse');
        }
        
        $productModel = new Product();
        $product = $productModel->getProductById($id);
        
        if (!$product) {
            $this->setFlash('error', 'Producto no encontrado');
            $this->redirect('/warehouse');
        }
        
        $movements = $productModel->getProductMovements($id);
        
        $this->loadView('warehouse/view', ['product' => $product, 'movements' => $movements]);
    }
    
    public function delete() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            
            if ($id) {
                $productModel = new Product();
                if ($productModel->deleteProduct($id)) {
                    $this->setFlash('success', 'Producto eliminado exitosamente');
                } else {
                    $this->setFlash('error', 'Error al eliminar el producto');
                }
            } else {
                $this->setFlash('error', 'ID de producto no válido');
            }
        }
        
        $this->redirect('/warehouse');
    }
}
?>