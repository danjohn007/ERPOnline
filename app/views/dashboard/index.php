<?php
$title = 'Dashboard';
$current_page = 'dashboard';
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">
            <i class="fas fa-tachometer-alt me-1"></i>
            Dashboard
        </li>
    </ol>
</nav>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h1 class="h3 mb-2">
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    Bienvenido al Sistema ERP Online
                </h1>
                <p class="text-muted mb-0">
                    Hola <strong><?= htmlspecialchars($user['nombre']) ?></strong>, 
                    este es tu panel de control empresarial.
                </p>
                <small class="text-muted">
                    Último acceso: <?= $user['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($user['ultimo_acceso'])) : 'Primer acceso' ?>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title text-primary mb-1">Clientes</h5>
                    <h2 class="mb-0 fw-bold"><?= number_format($stats['clientes']) ?></h2>
                    <small class="text-muted">Total registrados</small>
                </div>
                <div class="text-primary stats-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= url('/customers') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>
                    Ver detalles
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title text-success mb-1">Proveedores</h5>
                    <h2 class="mb-0 fw-bold"><?= number_format($stats['proveedores']) ?></h2>
                    <small class="text-muted">Total activos</small>
                </div>
                <div class="text-success stats-icon">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= url('/suppliers') ?>" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-eye me-1"></i>
                    Ver detalles
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title text-info mb-1">Productos</h5>
                    <h2 class="mb-0 fw-bold"><?= number_format($stats['productos']) ?></h2>
                    <small class="text-muted">En inventario</small>
                </div>
                <div class="text-info stats-icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= url('/warehouse') ?>" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-eye me-1"></i>
                    Ver detalles
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title <?= $stats['bajo_stock'] > 0 ? 'text-warning' : 'text-secondary' ?> mb-1">
                        Bajo Stock
                    </h5>
                    <h2 class="mb-0 fw-bold <?= $stats['bajo_stock'] > 0 ? 'text-warning' : 'text-secondary' ?>">
                        <?= number_format($stats['bajo_stock']) ?>
                    </h2>
                    <small class="text-muted">Productos críticos</small>
                </div>
                <div class="<?= $stats['bajo_stock'] > 0 ? 'text-warning' : 'text-secondary' ?> stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <?php if ($stats['bajo_stock'] > 0): ?>
                    <a href="<?= url('/warehouse?filter=low_stock') ?>" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-eye me-1"></i>
                        Ver productos
                    </a>
                <?php else: ?>
                    <span class="text-muted small">Stock en buen estado</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-rocket me-2"></i>
                    Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="<?= url('/customers/create') ?>" class="btn btn-outline-primary w-100 p-3">
                            <i class="fas fa-user-plus d-block mb-2" style="font-size: 2rem;"></i>
                            <strong>Nuevo Cliente</strong>
                            <br>
                            <small>Registrar cliente</small>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?= url('/suppliers/create') ?>" class="btn btn-outline-success w-100 p-3">
                            <i class="fas fa-truck-loading d-block mb-2" style="font-size: 2rem;"></i>
                            <strong>Nuevo Proveedor</strong>
                            <br>
                            <small>Registrar proveedor</small>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?= url('/warehouse/create') ?>" class="btn btn-outline-info w-100 p-3">
                            <i class="fas fa-box-open d-block mb-2" style="font-size: 2rem;"></i>
                            <strong>Nuevo Producto</strong>
                            <br>
                            <small>Agregar al inventario</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Actividad Reciente
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-plus text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">Sistema inicializado</div>
                            <small class="text-muted">Base de datos configurada</small>
                        </div>
                        <small class="text-muted">Hoy</small>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-cog text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">Configuración inicial</div>
                            <small class="text-muted">Módulos principales activados</small>
                        </div>
                        <small class="text-muted">Hoy</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Resumen del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="text-primary"><?= number_format($stats['usuarios']) ?></h3>
                        <small class="text-muted">Usuarios activos</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success"><?= number_format($stats['clientes'] + $stats['proveedores']) ?></h3>
                        <small class="text-muted">Socios comerciales</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <p class="mb-2">
                        <i class="fas fa-shield-alt text-success me-2"></i>
                        Sistema seguro y actualizado
                    </p>
                    <small class="text-muted">
                        Última sincronización: <?= date('d/m/Y H:i') ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>