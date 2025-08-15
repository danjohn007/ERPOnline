<?php
$title = 'Gestión de Inventario';
$current_page = 'warehouse';
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">
            <i class="fas fa-warehouse me-1"></i>
            Almacén
        </li>
    </ol>
</nav>

<!-- Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-2">
            <i class="fas fa-warehouse me-2 text-info"></i>
            Gestión de Inventario
        </h1>
        <p class="text-muted">Control de productos, stock y movimientos de almacén</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="btn-group" role="group">
            <a href="<?= url('/warehouse/create') ?>" class="btn btn-info">
                <i class="fas fa-plus me-2"></i>
                Nuevo Producto
            </a>
            <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Opciones</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?filter=low_stock">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Ver Bajo Stock
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">
                    <i class="fas fa-file-export me-2"></i>
                    Exportar Inventario
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" class="d-flex">
            <input type="text" 
                   class="form-control me-2" 
                   name="search" 
                   placeholder="Buscar por código, nombre o descripción..." 
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-outline-info">
                <i class="fas fa-search"></i>
            </button>
            <?php if ($search || $filter): ?>
                <a href="<?= url('/warehouse') ?>" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-times"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <small class="text-muted">
            <?php if ($filter === 'low_stock'): ?>
                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                Productos con bajo stock: <?= number_format($totalProducts) ?>
            <?php elseif ($search): ?>
                Se encontraron <?= number_format($totalProducts) ?> productos para "<?= htmlspecialchars($search) ?>"
            <?php else: ?>
                Total: <?= number_format($totalProducts) ?> productos en inventario
            <?php endif; ?>
        </small>
    </div>
</div>

<!-- Alerta de bajo stock -->
<?php if ($filter !== 'low_stock'): ?>
    <?php
    $productModel = new Product();
    $lowStockCount = $productModel->getLowStockCount();
    if ($lowStockCount > 0):
    ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h6><i class="fas fa-exclamation-triangle me-2"></i>Alerta de Inventario</h6>
        Hay <strong><?= $lowStockCount ?></strong> productos con stock por debajo del mínimo.
        <a href="?filter=low_stock" class="alert-link">Ver productos</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Tabla de productos -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-boxes me-2"></i>
            Inventario de Productos
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($products)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Ubicación</th>
                            <th>Precios</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr class="<?= $product['stock'] <= $product['stock_minimo'] ? 'table-warning' : '' ?>">
                                <td>
                                    <code><?= htmlspecialchars($product['codigo']) ?></code>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($product['nombre']) ?></strong>
                                        <?php if ($product['descripcion']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars(substr($product['descripcion'], 0, 60)) ?><?= strlen($product['descripcion']) > 60 ? '...' : '' ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($product['categoria_nombre']): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($product['categoria_nombre']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Sin categoría</span>
                                    <?php endif; ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($product['unidad_medida']) ?></small>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <h6 class="mb-1 <?= $product['stock'] <= $product['stock_minimo'] ? 'text-warning' : 'text-success' ?>">
                                            <?= number_format($product['stock']) ?>
                                        </h6>
                                        <div class="progress" style="height: 5px;">
                                            <?php 
                                            $percentage = $product['stock_maximo'] > 0 ? ($product['stock'] / $product['stock_maximo']) * 100 : 0;
                                            $percentage = min(100, max(0, $percentage));
                                            $colorClass = $product['stock'] <= $product['stock_minimo'] ? 'bg-warning' : 'bg-success';
                                            ?>
                                            <div class="progress-bar <?= $colorClass ?>" 
                                                 style="width: <?= $percentage ?>%"></div>
                                        </div>
                                        <small class="text-muted">
                                            Min: <?= $product['stock_minimo'] ?> | Max: <?= $product['stock_maximo'] ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <?= htmlspecialchars($product['ubicacion'] ?: 'No especificada') ?>
                                </td>
                                <td>
                                    <small>
                                        <strong>Compra:</strong> $<?= number_format($product['precio_compra'], 2) ?><br>
                                        <strong>Venta:</strong> $<?= number_format($product['precio_venta'], 2) ?>
                                        <?php 
                                        $margen = $product['precio_compra'] > 0 ? (($product['precio_venta'] - $product['precio_compra']) / $product['precio_compra']) * 100 : 0;
                                        ?>
                                        <br><span class="badge bg-<?= $margen > 20 ? 'success' : ($margen > 10 ? 'warning' : 'danger') ?> small">
                                            <?= number_format($margen, 1) ?>% margen
                                        </span>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($product['stock'] <= $product['stock_minimo']): ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Bajo Stock
                                        </span>
                                    <?php elseif ($product['stock'] == 0): ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>
                                            Agotado
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Disponible
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= url('/warehouse/view?id=' . $product['id']) ?>" 
                                           class="btn btn-outline-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= url('/warehouse/movement?id=' . $product['id']) ?>" 
                                           class="btn btn-outline-warning" 
                                           title="Movimiento">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>
                                        <a href="<?= url('/warehouse/edit?id=' . $product['id']) ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                onclick="confirmDelete(<?= $product['id'] ?>, '<?= addslashes($product['nombre']) ?>')"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay productos en el inventario</h5>
                <p class="text-muted mb-4">
                    <?php if ($search): ?>
                        No se encontraron productos que coincidan con su búsqueda.
                    <?php elseif ($filter === 'low_stock'): ?>
                        No hay productos con bajo stock en este momento.
                    <?php else: ?>
                        Comience agregando productos a su inventario.
                    <?php endif; ?>
                </p>
                <a href="<?= url('/warehouse/create') ?>" class="btn btn-info">
                    <i class="fas fa-plus me-2"></i>
                    Agregar Primer Producto
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Paginación -->
    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <nav aria-label="Paginación de productos">
                <ul class="pagination justify-content-center mb-0">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $filter ? '&filter=' . urlencode($filter) : '' ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $filter ? '&filter=' . urlencode($filter) : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $filter ? '&filter=' . urlencode($filter) : '' ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar el producto <strong id="productName"></strong>?</p>
                <p class="text-muted mb-0">Esta acción no se puede deshacer y se perderá el historial de movimientos.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/warehouse/delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Eliminar Producto
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('productName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>