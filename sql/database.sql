-- ==========================================
-- ERP Online - Sistema de Gestión Empresarial
-- Base de datos MySQL 5.7
-- ==========================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `erp_online` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `erp_online`;

-- ==========================================
-- TABLA: usuarios
-- ==========================================
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `rol` enum('administrador','gerente','usuario','contador','vendedor') DEFAULT 'usuario',
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- TABLA: clientes
-- ==========================================
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `rfc` varchar(13) NULL,
  `email` varchar(150) NULL,
  `telefono` varchar(20) NULL,
  `direccion` text NULL,
  `ciudad` varchar(100) NULL,
  `estado` varchar(100) NULL,
  `codigo_postal` varchar(10) NULL,
  `pais` varchar(50) DEFAULT 'México',
  `contacto_principal` varchar(100) NULL,
  `limite_credito` decimal(15,2) DEFAULT 0.00,
  `dias_credito` int(3) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_nombre` (`nombre`),
  INDEX `idx_rfc` (`rfc`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- TABLA: proveedores
-- ==========================================
CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `rfc` varchar(13) NULL,
  `email` varchar(150) NULL,
  `telefono` varchar(20) NULL,
  `direccion` text NULL,
  `ciudad` varchar(100) NULL,
  `estado` varchar(100) NULL,
  `codigo_postal` varchar(10) NULL,
  `pais` varchar(50) DEFAULT 'México',
  `contacto_principal` varchar(100) NULL,
  `dias_pago` int(3) DEFAULT 30,
  `cuenta_bancaria` varchar(20) NULL,
  `banco` varchar(100) NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_nombre` (`nombre`),
  INDEX `idx_rfc` (`rfc`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- TABLA: categorias_productos
-- ==========================================
CREATE TABLE `categorias_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- TABLA: productos
-- ==========================================
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL UNIQUE,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text NULL,
  `categoria_id` int(11) NULL,
  `unidad_medida` varchar(20) DEFAULT 'PZA',
  `precio_compra` decimal(15,2) DEFAULT 0.00,
  `precio_venta` decimal(15,2) DEFAULT 0.00,
  `stock` int(11) DEFAULT 0,
  `stock_minimo` int(11) DEFAULT 1,
  `stock_maximo` int(11) DEFAULT 100,
  `ubicacion` varchar(50) NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`categoria_id`) REFERENCES `categorias_productos`(`id`) ON DELETE SET NULL,
  INDEX `idx_codigo` (`codigo`),
  INDEX `idx_nombre` (`nombre`),
  INDEX `idx_stock` (`stock`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- TABLA: movimientos_inventario
-- ==========================================
CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida','ajuste','transferencia') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(15,2) DEFAULT 0.00,
  `referencia` varchar(100) NULL,
  `observaciones` text NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_movimiento` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`producto_id`) REFERENCES `productos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE RESTRICT,
  INDEX `idx_producto` (`producto_id`),
  INDEX `idx_tipo` (`tipo_movimiento`),
  INDEX `idx_fecha` (`fecha_movimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- TABLA: bancos
-- ==========================================
CREATE TABLE `bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `tipo_cuenta` enum('cheques','ahorro','inversion') DEFAULT 'cheques',
  `saldo_inicial` decimal(15,2) DEFAULT 0.00,
  `saldo_actual` decimal(15,2) DEFAULT 0.00,
  `moneda` varchar(3) DEFAULT 'MXN',
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_numero_cuenta` (`numero_cuenta`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- TABLA: movimientos_bancarios
-- ==========================================
CREATE TABLE `movimientos_bancarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banco_id` int(11) NOT NULL,
  `tipo_movimiento` enum('ingreso','egreso','transferencia') NOT NULL,
  `monto` decimal(15,2) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `referencia` varchar(100) NULL,
  `fecha_movimiento` date NOT NULL,
  `conciliado` tinyint(1) DEFAULT 0,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`banco_id`) REFERENCES `bancos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE RESTRICT,
  INDEX `idx_banco` (`banco_id`),
  INDEX `idx_fecha` (`fecha_movimiento`),
  INDEX `idx_tipo` (`tipo_movimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- DATOS DE EJEMPLO
-- ==========================================

-- Insertar usuario administrador por defecto
INSERT INTO `usuarios` (`nombre`, `email`, `password`, `rol`, `activo`) VALUES
('Administrador del Sistema', 'admin@erponline.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador', 1),
('Juan Pérez García', 'juan.perez@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gerente', 1),
('María López', 'maria.lopez@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendedor', 1),
('Carlos Rodríguez', 'carlos@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'contador', 1);

-- Insertar categorías de productos
INSERT INTO `categorias_productos` (`nombre`, `descripcion`) VALUES
('Electrónicos', 'Productos electrónicos y tecnológicos'),
('Oficina', 'Artículos de oficina y papelería'),
('Herramientas', 'Herramientas y equipos de trabajo'),
('Consumibles', 'Productos de consumo regular'),
('Servicios', 'Servicios diversos');

-- Insertar clientes de ejemplo
INSERT INTO `clientes` (`nombre`, `rfc`, `email`, `telefono`, `direccion`, `ciudad`, `estado`, `codigo_postal`, `contacto_principal`, `limite_credito`, `dias_credito`) VALUES
('Comercializadora ABC S.A. de C.V.', 'CABC850101XXX', 'ventas@abc.com.mx', '555-1234567', 'Av. Principal 123, Col. Centro', 'Ciudad de México', 'CDMX', '06000', 'Roberto Gómez', 50000.00, 30),
('Industrias XYZ S.A.', 'IXYZ900215XXX', 'compras@xyz.com.mx', '555-2345678', 'Calle Industrial 456, Zona Norte', 'Guadalajara', 'Jalisco', '44100', 'Ana Martínez', 75000.00, 45),
('Distribuidora del Norte', 'DNOR750620XXX', 'contacto@norte.com.mx', '555-3456789', 'Blvd. Norte 789, Col. Moderna', 'Monterrey', 'Nuevo León', '64000', 'Luis Hernández', 30000.00, 15),
('Servicios Empresariales DEF', 'SEDF880310XXX', 'info@def.com.mx', '555-4567890', 'Av. Reforma 321, Col. Roma', 'Ciudad de México', 'CDMX', '06700', 'Patricia Ruiz', 40000.00, 30);

-- Insertar proveedores de ejemplo
INSERT INTO `proveedores` (`nombre`, `rfc`, `email`, `telefono`, `direccion`, `ciudad`, `estado`, `codigo_postal`, `contacto_principal`, `dias_pago`, `cuenta_bancaria`, `banco`) VALUES
('Tecnología Avanzada S.A.', 'TECAV850915XXX', 'ventas@tecav.com.mx', '555-9876543', 'Parque Tecnológico 100', 'Ciudad de México', 'CDMX', '04520', 'Miguel Torres', 30, '1234567890123456', 'BBVA Bancomer'),
('Papelería y Oficina Total', 'PAOF901025XXX', 'ventas@oficina.com.mx', '555-8765432', 'Zona Comercial 200', 'Guadalajara', 'Jalisco', '44200', 'Sandra López', 15, '2345678901234567', 'Banamex'),
('Herramientas Industriales MX', 'HIMX870405XXX', 'info@herramientas.mx', '555-7654321', 'Distrito Industrial 300', 'Monterrey', 'Nuevo León', '64100', 'Ricardo Vega', 45, '3456789012345678', 'Santander'),
('Suministros Generales GHI', 'SGHI920718XXX', 'compras@ghi.com.mx', '555-6543210', 'Zona de Bodegas 400', 'Tijuana', 'Baja California', '22000', 'Elena Morales', 30, '4567890123456789', 'HSBC');

-- Insertar productos de ejemplo
INSERT INTO `productos` (`codigo`, `nombre`, `descripcion`, `categoria_id`, `unidad_medida`, `precio_compra`, `precio_venta`, `stock`, `stock_minimo`, `stock_maximo`, `ubicacion`) VALUES
('COMP001', 'Computadora de Escritorio', 'PC de escritorio Intel Core i5, 8GB RAM, 1TB HDD', 1, 'PZA', 8500.00, 12000.00, 15, 5, 50, 'A-01-01'),
('LAP001', 'Laptop Empresarial', 'Laptop HP Intel Core i7, 16GB RAM, 512GB SSD', 1, 'PZA', 15000.00, 20000.00, 8, 3, 25, 'A-01-02'),
('IMP001', 'Impresora Multifuncional', 'Impresora HP LaserJet Pro MFP', 1, 'PZA', 3500.00, 5200.00, 12, 2, 20, 'A-02-01'),
('PAP001', 'Papel Bond Carta', 'Papel bond blanco tamaño carta 500 hojas', 2, 'PAQ', 45.00, 65.00, 150, 20, 300, 'B-01-01'),
('BOL001', 'Bolígrafos Azules', 'Bolígrafos de tinta azul caja de 12 piezas', 2, 'CJA', 25.00, 40.00, 80, 10, 200, 'B-01-02'),
('TAL001', 'Taladro Eléctrico', 'Taladro eléctrico de 1/2 pulgada 600W', 3, 'PZA', 850.00, 1200.00, 6, 2, 15, 'C-01-01'),
('DES001', 'Desarmadores Set', 'Set de desarmadores planos y cruz 6 piezas', 3, 'SET', 120.00, 180.00, 25, 5, 50, 'C-01-02'),
('CAF001', 'Café Soluble', 'Café soluble premium frasco 200g', 4, 'FRA', 85.00, 120.00, 45, 10, 100, 'D-01-01'),
('AGU001', 'Agua Purificada', 'Agua purificada garrafón 20 litros', 4, 'GAR', 25.00, 35.00, 30, 5, 100, 'D-02-01'),
('SOP001', 'Soporte Técnico', 'Servicio de soporte técnico por hora', 5, 'HRA', 0.00, 450.00, 999, 0, 999, 'SERVICIO');

-- Insertar bancos de ejemplo
INSERT INTO `bancos` (`nombre`, `numero_cuenta`, `tipo_cuenta`, `saldo_inicial`, `saldo_actual`, `moneda`) VALUES
('BBVA Bancomer - Cuenta Principal', '0123456789', 'cheques', 250000.00, 250000.00, 'MXN'),
('Banamex - Cuenta Nómina', '9876543210', 'cheques', 150000.00, 150000.00, 'MXN'),
('Santander - Cuenta de Ahorros', '5555666677', 'ahorro', 75000.00, 75000.00, 'MXN'),
('HSBC - Cuenta USD', '1111222233', 'cheques', 10000.00, 10000.00, 'USD');

-- Insertar algunos movimientos de inventario de ejemplo
INSERT INTO `movimientos_inventario` (`producto_id`, `tipo_movimiento`, `cantidad`, `precio_unitario`, `referencia`, `observaciones`, `usuario_id`) VALUES
(1, 'entrada', 15, 8500.00, 'FACT-001', 'Compra inicial de inventario', 1),
(2, 'entrada', 8, 15000.00, 'FACT-002', 'Compra inicial de inventario', 1),
(3, 'entrada', 12, 3500.00, 'FACT-003', 'Compra inicial de inventario', 1),
(4, 'entrada', 150, 45.00, 'FACT-004', 'Compra inicial de inventario', 1),
(5, 'entrada', 80, 25.00, 'FACT-005', 'Compra inicial de inventario', 1);

-- Insertar algunos movimientos bancarios de ejemplo
INSERT INTO `movimientos_bancarios` (`banco_id`, `tipo_movimiento`, `monto`, `concepto`, `referencia`, `fecha_movimiento`, `usuario_id`) VALUES
(1, 'ingreso', 250000.00, 'Depósito inicial de capital', 'DEP-001', CURDATE(), 1),
(2, 'ingreso', 150000.00, 'Transferencia para nómina', 'TRANS-001', CURDATE(), 1),
(3, 'ingreso', 75000.00, 'Apertura cuenta de ahorros', 'DEP-002', CURDATE(), 1),
(4, 'ingreso', 10000.00, 'Apertura cuenta USD', 'DEP-003', CURDATE(), 1);

-- ==========================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- ==========================================

-- Índices para mejorar el rendimiento de consultas frecuentes
CREATE INDEX idx_clientes_nombre_activo ON clientes(nombre, activo);
CREATE INDEX idx_proveedores_nombre_activo ON proveedores(nombre, activo);
CREATE INDEX idx_productos_codigo_activo ON productos(codigo, activo);
CREATE INDEX idx_productos_stock_minimo ON productos(stock, stock_minimo);

-- ==========================================
-- COMENTARIOS FINALES
-- ==========================================

-- Base de datos creada exitosamente
-- Contraseña por defecto para todos los usuarios de ejemplo: "secret123"
-- Hash generado con: password_hash('secret123', PASSWORD_DEFAULT)

-- Para conectarse como administrador:
-- Email: admin@erponline.com
-- Contraseña: secret123