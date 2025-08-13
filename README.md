# ERP Online - Sistema de Gestión Empresarial

![ERP Online](https://img.shields.io/badge/ERP-Online-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-green.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)

## 📋 Descripción

ERP Online es un sistema integral de gestión empresarial desarrollado en PHP puro (sin frameworks) con arquitectura MVC, diseñado para pequeñas y medianas empresas que requieren una solución completa para la administración de sus procesos comerciales.

## 🚀 Características Principales

### Módulos Incluidos

1. **🏦 Bancos**
   - Registro y administración de cuentas bancarias
   - Conciliaciones bancarias automáticas y manuales
   - Control de ingresos y egresos
   - Integración con estados de cuenta electrónicos

2. **👥 Clientes / CRM / CFDI**
   - Registro de clientes con datos fiscales y comerciales
   - Seguimiento de prospectos y oportunidades de venta
   - Emisión y timbrado de facturas CFDI 4.0
   - Historial de compras y pagos

3. **🚚 Proveedores**
   - Alta y gestión de proveedores con datos fiscales
   - Control de órdenes de compra
   - Recepción de facturas electrónicas
   - Historial de compras y pagos

4. **📦 Almacén**
   - Control de inventarios por almacén y ubicación
   - Entradas, salidas y transferencias
   - Trazabilidad de productos (lotes, series, fechas de caducidad)
   - Reportes de existencias y valorización

5. **📊 Contabilidad / Activos Fijos**
   - Registro contable automático desde transacciones
   - Catálogo de cuentas configurable
   - Depreciación y control de activos fijos
   - Generación de estados financieros

6. **💼 Nómina / RRHH**
   - Control de empleados, contratos y puestos
   - Cálculo de nómina con timbrado CFDI
   - Gestión de incidencias, vacaciones y prestaciones
   - Reportes de obligaciones fiscales

7. **📈 Business Intelligence**
   - Dashboards personalizables con KPIs
   - Reportes analíticos por módulo
   - Exportación de datos a Excel, PDF y CSV
   - Filtros avanzados y comparativas

8. **🌐 ALPHA ERP® Web**
   - Acceso 100% en la nube vía navegador
   - Gestión multiempresa y multiusuario
   - Control de permisos y roles
   - Respaldo automático de información

9. **🛒 E-Commerce**
   - Integración con tienda online
   - Sincronización automática de inventarios y precios
   - Procesamiento de pagos electrónicos
   - Gestión de pedidos y envíos

10. **🛍️ E-Procurement**
    - Portal para requisiciones internas
    - Cotizaciones en línea con proveedores
    - Aprobación de compras por flujo
    - Seguimiento de órdenes

11. **🏷️ Atributos**
    - Definición de campos personalizados
    - Filtrado y segmentación por atributos
    - Control de variantes (color, talla, modelo)

## 🔧 Requisitos del Sistema

### Servidor Web
- **Apache 2.4+** con mod_rewrite habilitado
- **PHP 7.4+** con extensiones:
  - PDO (MySQL)
  - mbstring
  - openssl
  - curl
  - gd
  - zip
- **MySQL 5.7+** o **MariaDB 10.2+**

### Recursos Mínimos
- **RAM**: 512 MB (recomendado 1 GB)
- **Espacio en disco**: 500 MB (recomendado 2 GB para datos)
- **CPU**: 1 núcleo (recomendado 2 núcleos)

## 📥 Instalación

### 1. Descarga del Sistema

```bash
# Clonar el repositorio
git clone https://github.com/danjohn007/ERPOnline.git
cd ERPOnline

# O descargar el ZIP y extraer
wget https://github.com/danjohn007/ERPOnline/archive/main.zip
unzip main.zip
```

### 2. Configuración del Servidor Apache

#### Opción A: Hosting Compartido

1. Subir todos los archivos al directorio `public_html` o `www` de su hosting
2. Asegurarse de que el archivo `.htaccess` esté presente
3. Verificar que mod_rewrite esté habilitado

#### Opción B: Servidor Local (XAMPP/WAMP/MAMP)

1. Copiar la carpeta del proyecto a `htdocs` (XAMPP) o `www` (WAMP)
2. Iniciar Apache y MySQL
3. Acceder a `http://localhost/ERPOnline`

#### Opción C: Servidor VPS/Dedicado

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install apache2 php mysql-server php-mysql php-mbstring php-curl php-gd php-zip

# CentOS/RHEL
sudo yum install httpd php mysql-server php-mysql php-mbstring php-curl php-gd php-zip

# Habilitar mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**Configurar Virtual Host:**

```apache
<VirtualHost *:80>
    ServerName erponline.local
    DocumentRoot /var/www/html/ERPOnline
    
    <Directory /var/www/html/ERPOnline>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/erponline_error.log
    CustomLog ${APACHE_LOG_DIR}/erponline_access.log combined
</VirtualHost>
```

### 3. Configuración de la Base de Datos

#### Crear la Base de Datos

```bash
# Acceder a MySQL
mysql -u root -p

# Crear base de datos y usuario
CREATE DATABASE erp_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'erp_user'@'localhost' IDENTIFIED BY 'password_segura';
GRANT ALL PRIVILEGES ON erp_online.* TO 'erp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Importar el Schema

```bash
# Importar la estructura y datos de ejemplo
mysql -u erp_user -p erp_online < sql/database.sql
```

### 4. Configuración de la Aplicación

Editar el archivo `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'erp_online');
define('DB_USER', 'erp_user');
define('DB_PASS', 'password_segura');
define('DB_CHARSET', 'utf8mb4');
```

### 5. Configuración de Permisos

```bash
# Linux/Unix
sudo chown -R www-data:www-data /var/www/html/ERPOnline
sudo chmod -R 755 /var/www/html/ERPOnline
```

## 🔐 Primer Acceso

### Credenciales por Defecto

- **URL**: `http://localhost/ERPOnline` o su dominio
- **Usuario**: `admin@erponline.com`
- **Contraseña**: `secret123`

### Configuración Inicial

1. **Cambiar contraseña del administrador**
2. **Crear usuarios adicionales** con roles específicos
3. **Configurar información de la empresa**
4. **Personalizar módulos** según necesidades

## 🛠️ Arquitectura del Sistema

### Estructura de Directorios

```
ERPOnline/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/         # Modelos de datos
│   └── views/          # Vistas de la aplicación
├── config/             # Archivos de configuración
├── public/             # Recursos públicos (CSS, JS, imágenes)
├── sql/               # Scripts de base de datos
├── .htaccess          # Configuración de Apache
└── index.php          # Punto de entrada principal
```

### Patrón MVC

- **Models**: Gestión de datos y lógica de negocio
- **Views**: Presentación e interfaz de usuario
- **Controllers**: Lógica de control y enrutamiento

## 🔒 Seguridad

### Características Implementadas

- **Autenticación segura** con hash de contraseñas
- **Control de sesiones** con tiempo de expiración
- **Validación de entrada** en todos los formularios
- **Prevención de SQL Injection** con consultas preparadas
- **Protección XSS** con escape de salida
- **Control de acceso** por roles y permisos

### Recomendaciones Adicionales

```apache
# En .htaccess - Headers de seguridad adicionales
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
```

## 📊 Gestión de Datos

### Base de Datos

- **Motor**: MySQL 5.7+ / MariaDB 10.2+
- **Charset**: UTF8MB4 (soporte completo Unicode)
- **Transacciones**: InnoDB para integridad referencial
- **Índices**: Optimizados para consultas frecuentes

### Backup Automático

```bash
# Script de respaldo diario
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u erp_user -p erp_online > backup_erp_$DATE.sql
gzip backup_erp_$DATE.sql
```

## 🚀 Uso del Sistema

### Flujo de Trabajo Típico

1. **Configurar empresa** y usuarios
2. **Registrar clientes** y proveedores
3. **Cargar productos** al inventario
4. **Configurar términos** comerciales
5. **Generar facturas** y documentos
6. **Consultar reportes** y KPIs

### Módulos Principales

#### Gestión de Clientes
- Registro completo con datos fiscales
- Seguimiento de límites de crédito
- Historial de transacciones
- Reportes de cartera

#### Control de Inventarios
- Movimientos de entrada/salida
- Control de stock mínimo/máximo
- Trazabilidad completa
- Valorización de inventarios

#### Facturación Electrónica
- Generación CFDI 4.0
- Timbrado automático
- Envío por email
- Portal de consulta cliente

## 🔧 Mantenimiento

### Tareas Rutinarias

```bash
# Optimizar base de datos
mysql -u erp_user -p -e "OPTIMIZE TABLE erp_online.*;"

# Limpiar logs antiguos
find /var/log/apache2/ -name "*.log" -mtime +30 -delete

# Verificar espacio en disco
df -h
```

### Actualizaciones

1. **Backup** completo antes de actualizar
2. **Descargar** nueva versión
3. **Ejecutar** scripts de migración
4. **Verificar** funcionamiento

## 📞 Soporte Técnico

### Logs del Sistema

- **Apache**: `/var/log/apache2/error.log`
- **PHP**: Configurado para mostrar errores en desarrollo
- **MySQL**: `/var/log/mysql/error.log`

### Problemas Comunes

| Problema | Solución |
|----------|----------|
| Error 500 | Verificar permisos y logs de Apache |
| Página en blanco | Revisar errores de PHP |
| No carga CSS | Verificar rutas y mod_rewrite |
| Error de BD | Confirmar credenciales y conexión |

## 🤝 Contribución

### Desarrollo Local

```bash
# Configurar entorno de desarrollo
git clone https://github.com/danjohn007/ERPOnline.git
cd ERPOnline

# Instalar base de datos de desarrollo
mysql -u root -p < sql/database.sql
```

### Estándares de Código

- **PSR-4** para autoloading
- **Documentación** en español
- **Comentarios** descriptivos
- **Validación** en cliente y servidor

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para detalles.

## 📋 Changelog

### v1.0.0 (2024-01-01)
- ✅ Implementación inicial del sistema
- ✅ Módulos de Clientes, Proveedores y Almacén
- ✅ Autenticación y control de usuarios
- ✅ Dashboard con KPIs principales
- ✅ Arquitectura MVC completa
- ✅ Integración Bootstrap 5.3
- ✅ Base de datos MySQL optimizada

---

## 🌟 Características Técnicas

- **Framework**: PHP Puro (sin dependencias)
- **Base de Datos**: MySQL 5.7+ con InnoDB
- **Frontend**: Bootstrap 5.3 + Font Awesome 6.4
- **Arquitectura**: MVC (Model-View-Controller)
- **Seguridad**: Hash de contraseñas, consultas preparadas, validación completa
- **Compatibilidad**: Multi-navegador, responsive design
- **SEO**: URLs amigables, meta tags optimizados

**Desarrollado con ❤️ para empresas mexicanas**
