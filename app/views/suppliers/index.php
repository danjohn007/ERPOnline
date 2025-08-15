<?php
$title = 'Gestión de Proveedores';
$current_page = 'suppliers';
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">
            <i class="fas fa-truck me-1"></i>
            Proveedores
        </li>
    </ol>
</nav>

<!-- Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-2">
            <i class="fas fa-truck me-2 text-success"></i>
            Gestión de Proveedores
        </h1>
        <p class="text-muted">Administre la información de sus proveedores y socios comerciales</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= url('/suppliers/create') ?>" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>
            Nuevo Proveedor
        </a>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" class="d-flex">
            <input type="text" 
                   class="form-control me-2" 
                   name="search" 
                   placeholder="Buscar por nombre, email, RFC o teléfono..." 
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-outline-success">
                <i class="fas fa-search"></i>
            </button>
            <?php if ($search): ?>
                <a href="<?= url('/suppliers') ?>" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-times"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <small class="text-muted">
            <?php if ($search): ?>
                Se encontraron <?= number_format($totalSuppliers) ?> resultados para "<?= htmlspecialchars($search) ?>"
            <?php else: ?>
                Total: <?= number_format($totalSuppliers) ?> proveedores registrados
            <?php endif; ?>
        </small>
    </div>
</div>

<!-- Tabla de proveedores -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Lista de Proveedores
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($suppliers)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Ciudad</th>
                            <th>Días de Pago</th>
                            <th>Banco</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $supplier): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($supplier['nombre'], 0, 2)) ?>
                                            </div>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($supplier['nombre']) ?></strong>
                                            <?php if ($supplier['contacto_principal']): ?>
                                                <br><small class="text-muted">
                                                    Contacto: <?= htmlspecialchars($supplier['contacto_principal']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($supplier['rfc']): ?>
                                        <code><?= htmlspecialchars($supplier['rfc']) ?></code>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($supplier['email']): ?>
                                        <a href="mailto:<?= htmlspecialchars($supplier['email']) ?>">
                                            <?= htmlspecialchars($supplier['email']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($supplier['telefono']): ?>
                                        <a href="tel:<?= htmlspecialchars($supplier['telefono']) ?>">
                                            <?= htmlspecialchars($supplier['telefono']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($supplier['ciudad']): ?>
                                        <?= htmlspecialchars($supplier['ciudad']) ?>
                                        <?php if ($supplier['estado']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($supplier['estado']) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No especificada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $supplier['dias_pago'] <= 15 ? 'success' : ($supplier['dias_pago'] <= 30 ? 'warning' : 'info') ?>">
                                        <?= $supplier['dias_pago'] ?> días
                                    </span>
                                </td>
                                <td>
                                    <?php if ($supplier['banco']): ?>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($supplier['banco']) ?>
                                            <?php if ($supplier['cuenta_bancaria']): ?>
                                                <br><code class="small">****<?= substr($supplier['cuenta_bancaria'], -4) ?></code>
                                            <?php endif; ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= url('/suppliers/view?id=' . $supplier['id']) ?>" 
                                           class="btn btn-outline-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= url('/suppliers/edit?id=' . $supplier['id']) ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                onclick="confirmDelete(<?= $supplier['id'] ?>, '<?= addslashes($supplier['nombre']) ?>')"
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
                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay proveedores registrados</h5>
                <p class="text-muted mb-4">
                    <?php if ($search): ?>
                        No se encontraron proveedores que coincidan con su búsqueda.
                    <?php else: ?>
                        Comience agregando su primer proveedor al sistema.
                    <?php endif; ?>
                </p>
                <a href="<?= url('/suppliers/create') ?>" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>
                    Agregar Primer Proveedor
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Paginación -->
    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <nav aria-label="Paginación de proveedores">
                <ul class="pagination justify-content-center mb-0">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
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
                <p>¿Está seguro de que desea eliminar el proveedor <strong id="supplierName"></strong>?</p>
                <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/suppliers/delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Eliminar Proveedor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('supplierName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>