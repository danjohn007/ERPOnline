<?php
$title = 'Nuevo Cliente';
$current_page = 'customers';
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/customers">Clientes</a></li>
        <li class="breadcrumb-item active">Nuevo Cliente</li>
    </ol>
</nav>

<!-- Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-2">
            <i class="fas fa-user-plus me-2 text-primary"></i>
            Nuevo Cliente
        </h1>
        <p class="text-muted">Registre un nuevo cliente en el sistema</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="/customers" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver a la Lista
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>
                    Información del Cliente
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($errors['general']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- Información básica -->
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Datos Básicos
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nombre / Razón Social *
                                </label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?= htmlspecialchars($data['nombre'] ?? '') ?>"
                                       required>
                                <?php if (isset($errors['nombre'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['nombre']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rfc" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>
                                    RFC
                                </label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['rfc']) ? 'is-invalid' : '' ?>" 
                                       id="rfc" 
                                       name="rfc" 
                                       value="<?= htmlspecialchars($data['rfc'] ?? '') ?>"
                                       maxlength="13"
                                       style="text-transform: uppercase;">
                                <?php if (isset($errors['rfc'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['rfc']) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-text">Registro Federal de Contribuyentes (opcional)</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email
                                </label>
                                <input type="email" 
                                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Teléfono
                                </label>
                                <input type="tel" 
                                       class="form-control <?= isset($errors['telefono']) ? 'is-invalid' : '' ?>" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?= htmlspecialchars($data['telefono'] ?? '') ?>">
                                <?php if (isset($errors['telefono'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['telefono']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="contacto_principal" class="form-label">
                                    <i class="fas fa-user-tie me-1"></i>
                                    Contacto Principal
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="contacto_principal" 
                                       name="contacto_principal" 
                                       value="<?= htmlspecialchars($data['contacto_principal'] ?? '') ?>">
                                <div class="form-text">Nombre de la persona de contacto principal</div>
                            </div>
                        </div>
                        
                        <!-- Dirección -->
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3 mt-2">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Dirección
                            </h6>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">
                                    <i class="fas fa-home me-1"></i>
                                    Dirección
                                </label>
                                <textarea class="form-control" 
                                          id="direccion" 
                                          name="direccion" 
                                          rows="2"><?= htmlspecialchars($data['direccion'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ciudad" class="form-label">
                                    <i class="fas fa-city me-1"></i>
                                    Ciudad
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="ciudad" 
                                       name="ciudad" 
                                       value="<?= htmlspecialchars($data['ciudad'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-map me-1"></i>
                                    Estado
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="estado" 
                                       name="estado" 
                                       value="<?= htmlspecialchars($data['estado'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="codigo_postal" class="form-label">
                                    <i class="fas fa-mail-bulk me-1"></i>
                                    Código Postal
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="codigo_postal" 
                                       name="codigo_postal" 
                                       value="<?= htmlspecialchars($data['codigo_postal'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pais" class="form-label">
                                    <i class="fas fa-globe me-1"></i>
                                    País
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="pais" 
                                       name="pais" 
                                       value="<?= htmlspecialchars($data['pais'] ?? 'México') ?>">
                            </div>
                        </div>
                        
                        <!-- Términos comerciales -->
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3 mt-2">
                                <i class="fas fa-handshake me-1"></i>
                                Términos Comerciales
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="limite_credito" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    Límite de Crédito
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="limite_credito" 
                                           name="limite_credito" 
                                           value="<?= htmlspecialchars($data['limite_credito'] ?? '0.00') ?>"
                                           step="0.01"
                                           min="0">
                                </div>
                                <div class="form-text">Límite de crédito autorizado para este cliente</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dias_credito" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Días de Crédito
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="dias_credito" 
                                       name="dias_credito" 
                                       value="<?= htmlspecialchars($data['dias_credito'] ?? '0') ?>"
                                       min="0"
                                       max="365">
                                <div class="form-text">Número de días para pago a crédito</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="/customers" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Guardar Cliente
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    Consejos
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-1"></i> Información importante:</h6>
                    <ul class="mb-0 small">
                        <li>El nombre es el único campo obligatorio</li>
                        <li>El RFC debe tener 12 o 13 caracteres</li>
                        <li>El límite de crédito controla las ventas a crédito</li>
                        <li>Los días de crédito definen el plazo de pago</li>
                    </ul>
                </div>
                
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle me-1"></i> Después de crear:</h6>
                    <ul class="mb-0 small">
                        <li>Podrá generar facturas para este cliente</li>
                        <li>Llevar seguimiento de sus compras</li>
                        <li>Gestionar su historial de pagos</li>
                        <li>Configurar descuentos especiales</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Formatear RFC en mayúsculas
document.getElementById('rfc').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

// Validación del formulario
(function() {
    'use strict';
    window.addEventListener('load', function() {
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }, false);
})();
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>