<?php
$title = 'Gestión de Clientes';
$current_page = 'customers';
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">
            <i class="fas fa-users me-1"></i>
            Clientes
        </li>
    </ol>
</nav>

<!-- Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-2">
            <i class="fas fa-users me-2 text-primary"></i>
            Gestión de Clientes
        </h1>
        <p class="text-muted">Administre la información de sus clientes y relaciones comerciales</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="/customers/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Nuevo Cliente
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
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i>
            </button>
            <?php if ($search): ?>
                <a href="/customers" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-times"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <small class="text-muted">
            <?php if ($search): ?>
                Se encontraron <?= number_format($totalCustomers) ?> resultados para "<?= htmlspecialchars($search) ?>"
            <?php else: ?>
                Total: <?= number_format($totalCustomers) ?> clientes registrados
            <?php endif; ?>
        </small>
    </div>
</div>

<!-- Tabla de clientes -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Lista de Clientes
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($customers)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Ciudad</th>
                            <th>Límite Crédito</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($customer['nombre'], 0, 2)) ?>
                                            </div>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($customer['nombre']) ?></strong>
                                            <?php if ($customer['contacto_principal']): ?>
                                                <br><small class="text-muted">
                                                    Contacto: <?= htmlspecialchars($customer['contacto_principal']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($customer['rfc']): ?>
                                        <code><?= htmlspecialchars($customer['rfc']) ?></code>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($customer['email']): ?>
                                        <a href="mailto:<?= htmlspecialchars($customer['email']) ?>">
                                            <?= htmlspecialchars($customer['email']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($customer['telefono']): ?>
                                        <a href="tel:<?= htmlspecialchars($customer['telefono']) ?>">
                                            <?= htmlspecialchars($customer['telefono']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($customer['ciudad']): ?>
                                        <?= htmlspecialchars($customer['ciudad']) ?>
                                        <?php if ($customer['estado']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($customer['estado']) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No especificada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $customer['limite_credito'] > 0 ? 'success' : 'secondary' ?>">
                                        $<?= number_format($customer['limite_credito'], 2) ?>
                                    </span>
                                    <?php if ($customer['dias_credito'] > 0): ?>
                                        <br><small class="text-muted"><?= $customer['dias_credito'] ?> días</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/customers/view?id=<?= $customer['id'] ?>" 
                                           class="btn btn-outline-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/customers/edit?id=<?= $customer['id'] ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                onclick="confirmDelete(<?= $customer['id'] ?>, '<?= addslashes($customer['nombre']) ?>')"
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
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay clientes registrados</h5>
                <p class="text-muted mb-4">
                    <?php if ($search): ?>
                        No se encontraron clientes que coincidan con su búsqueda.
                    <?php else: ?>
                        Comience agregando su primer cliente al sistema.
                    <?php endif; ?>
                </p>
                <a href="/customers/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Agregar Primer Cliente
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Paginación -->
    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <nav aria-label="Paginación de clientes">
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
                <p>¿Está seguro de que desea eliminar el cliente <strong id="customerName"></strong>?</p>
                <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/customers/delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Eliminar Cliente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('customerName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>