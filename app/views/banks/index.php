<?php
$title = 'Gestión de Bancos';
$current_page = 'banks';
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">
            <i class="fas fa-university me-1"></i>
            Bancos
        </li>
    </ol>
</nav>

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-university text-primary" style="font-size: 4rem; opacity: 0.7;"></i>
                </div>
                <h1 class="h3 mb-3">
                    <i class="fas fa-university me-2 text-primary"></i>
                    Gestión de Bancos
                </h1>
                <p class="text-muted mb-4">
                    Módulo para la gestión de cuentas bancarias, transferencias y conciliación bancaria.
                </p>
                <div class="alert alert-info d-inline-block">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Próximamente:</strong> Este módulo estará disponible en una futura actualización del sistema.
                </div>
                <div class="mt-4">
                    <a href="<?= url('/dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>