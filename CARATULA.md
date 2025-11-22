# CARÁTULA DEL PROYECTO
## Sistema de Gestión Veterinaria - Módulo de Seguridad, Auditoría y Respaldo

---

### INFORMACIÓN DEL PROYECTO

**Nombre del Proyecto:** Sistema de Gestión Veterinaria  
**Módulo:** Seguridad, Auditoría y Respaldo de Datos  
**Grupo:** 6  
**Fecha:** Diciembre 2024  
**Lenguaje de Programación:** PHP  
**Base de Datos:** MySQL  

---

### INTEGRANTES Y PARTICIPACIÓN

#### INTEGRANTE 1
**Nombre:** [Nombre del Integrante 1]  
**Porcentaje de Participación:** 30%  

**Descripción del Trabajo Realizado:**
- Desarrollo e implementación del sistema de seguridad del proyecto
- Creación de la clase `util/Seguridad.php` con funcionalidades de protección contra ataques comunes:
  - Implementación de sesiones seguras con timeout configurable (30 minutos)
  - Protección contra fuerza bruta con límite de 5 intentos fallidos y bloqueo temporal de 15 minutos
  - Generación y validación de tokens CSRF para prevenir ataques de falsificación de solicitudes
  - Sanitización de entradas para prevenir ataques XSS (Cross-Site Scripting)
  - Validación de contraseñas con requisitos de seguridad (mínimo 8 caracteres, mayúsculas, minúsculas y números)
  - Validación de correos electrónicos
  - Regeneración periódica de IDs de sesión para prevenir session hijacking
  - Configuración de cookies seguras (HttpOnly, Secure, SameSite)
- Mejora del controlador de autenticación (`controller/LoginController.php`):
  - Integración del sistema de seguridad con verificación de intentos de login
  - Integración con el sistema de auditoría para registrar accesos y logout
  - Manejo de intentos fallidos de autenticación con bloqueo temporal
  - Regeneración de ID de sesión después de login exitoso
  - Sanitización de entradas de usuario
- Mejora del sistema de enrutamiento (`router.php`):
  - Agregado de nuevas rutas para auditoría y respaldo
  - Protección de nuevas rutas privadas mediante verificación de sesión
- Actualización del archivo principal (`index.php`):
  - Integración de sesiones seguras globales
  - Uso de la clase Seguridad para inicialización de sesión

---

#### INTEGRANTE 2
**Nombre:** [Nombre del Integrante 2]  
**Porcentaje de Participación:** 25%  

**Descripción del Trabajo Realizado:**
- Desarrollo e implementación del sistema de auditoría completo
- Diseño y creación de la estructura de base de datos para auditoría:
  - Creación del script SQL (`database/auditoria.sql`) con la tabla `auditoria`
  - Definición de campos para registro de acciones, usuarios, tablas afectadas, datos anteriores y nuevos
  - Implementación de índices para optimización de consultas
  - Configuración de claves foráneas con integridad referencial
- Desarrollo del modelo de datos (`model/Auditoria.php`):
  - Clase con todas las propiedades y métodos getter/setter
  - Definición de constantes para tipos de acciones (LOGIN, LOGOUT, CREAR, ACTUALIZAR, ELIMINAR, CONSULTAR, RESPALDO, RESTAURAR)
- Implementación del DAO de auditoría (`model/AuditoriaDAO.php`):
  - Método para registrar acciones en la base de datos
  - Método para listar registros con filtros avanzados (usuario, acción, tabla, fechas)
  - Método para obtener estadísticas de auditoría agrupadas por acción
  - Optimización de consultas con prepared statements
- Desarrollo del servicio de auditoría (`service/AuditoriaService.php`):
  - Lógica de negocio para el registro de acciones
  - Obtención automática de IP del cliente (soporte para proxies)
  - Captura de User Agent del navegador
  - Integración con el DAO para operaciones de base de datos
- Desarrollo del controlador de auditoría (`controller/AuditoriaController.php`):
  - Endpoints para listar registros de auditoría
  - Endpoints para obtener estadísticas
  - Validación de autenticación y autorización
- Desarrollo de la interfaz de usuario (`view/vAuditoria.php`):
  - Vista completa con filtros de búsqueda (acción, tabla, fechas)
  - Tabla responsiva para visualización de registros
  - Panel de estadísticas con tarjetas informativas
  - Modal para visualizar detalles completos de cada registro
  - Implementación de JavaScript para interacción dinámica con AJAX

---

#### INTEGRANTE 3
**Nombre:** [Nombre del Integrante 3]  
**Porcentaje de Participación:** 25%  

**Descripción del Trabajo Realizado:**
- Desarrollo e implementación del sistema de respaldo y restauración de base de datos
- Creación de la clase `util/Respaldo.php` con funcionalidades completas:
  - Implementación de respaldo automático usando `mysqldump` cuando está disponible
  - Sistema de respaldo manual como fallback usando consultas SQL nativas
  - Exportación completa de estructura y datos de todas las tablas
  - Manejo de transacciones para garantizar integridad de datos
  - Sistema de metadatos para cada respaldo (comentarios, hash MD5, fecha, tamaño)
  - Funcionalidad de restauración de respaldos con validación de archivos
  - Eliminación segura de respaldos antiguos
  - Listado de respaldos disponibles con información detallada
  - Detección automática de la ruta de `mysqldump` en diferentes sistemas operativos
  - Creación automática del directorio de respaldos si no existe
- Desarrollo del controlador de respaldo (`controller/RespaldoController.php`):
  - Endpoint para crear nuevos respaldos con comentarios opcionales
  - Endpoint para listar todos los respaldos disponibles
  - Endpoint para restaurar un respaldo específico
  - Endpoint para eliminar respaldos
  - Integración con el sistema de auditoría para registrar todas las operaciones
  - Validación de autenticación y autorización
  - Manejo de errores y excepciones
- Desarrollo de la interfaz de usuario (`view/vRespaldo.php`):
  - Formulario para crear respaldos con campo de comentarios
  - Tabla responsiva para visualizar todos los respaldos disponibles
  - Funcionalidad de restauración con modal de confirmación y advertencias
  - Funcionalidad de eliminación con confirmación de seguridad
  - Indicadores visuales de carga durante operaciones
  - Formateo automático de tamaños de archivo (Bytes, KB, MB, GB)
  - Implementación completa de JavaScript para interacción dinámica
- Integración del sistema de respaldo con el sistema de auditoría para rastrear todas las operaciones

---

#### INTEGRANTE 4
**Nombre:** [Nombre del Integrante 4]  
**Porcentaje de Participación:** 20%  

**Descripción del Trabajo Realizado:**
- Integración del sistema de auditoría en controladores existentes:
  - Modificación de `controller/UsuarioController.php` para registrar todas las operaciones CRUD en auditoría
  - Integración de sanitización de entradas usando la clase Seguridad
  - Validación mejorada de contraseñas y emails
  - Registro de datos anteriores y nuevos en operaciones de actualización
  - Registro de datos eliminados en operaciones de eliminación
- Mejora del dashboard principal (`view/dashboard.php`):
  - Actualización de la interfaz con tarjetas de navegación a los nuevos módulos
  - Agregado de enlaces a Auditoría y Respaldos
  - Mejora del diseño con Bootstrap
  - Integración de protección XSS en la visualización de datos
- Desarrollo de páginas de error:
  - Implementación de página 404 personalizada (`view/errores/404.php`)
  - Mejora del manejo de errores en el router
- Configuración de seguridad:
  - Creación de archivo `.htaccess` en directorio de respaldos para protección
  - Configuración de `.gitkeep` para mantener estructura de directorios
- Integración y pruebas de todos los módulos desarrollados por el Grupo 6
- Documentación del proyecto

---

### RESUMEN DE PARTICIPACIÓN

| Integrante | Porcentaje | Área Principal |
|------------|------------|----------------|
| Integrante 1 | 30% | Sistema de Seguridad |
| Integrante 2 | 25% | Sistema de Auditoría |
| Integrante 3 | 25% | Sistema de Respaldo |
| Integrante 4 | 20% | Integración y Mejoras |
| **TOTAL** | **100%** | |

---

### TECNOLOGÍAS UTILIZADAS

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (jQuery), Bootstrap 4.6.2
- **Librerías:** DomPDF (para generación de PDFs)
- **Gestión de Dependencias:** Composer
- **Arquitectura:** MVC (Modelo-Vista-Controlador)
- **Patrón de Diseño:** DAO (Data Access Object)

---

### FUNCIONALIDADES IMPLEMENTADAS POR EL GRUPO 6

1. ✅ Sistema de seguridad completo (`util/Seguridad.php`):
   - Sesiones seguras con timeout y regeneración de ID
   - Protección contra fuerza bruta (límite de intentos)
   - Tokens CSRF para prevenir ataques de falsificación
   - Sanitización de entradas contra XSS
   - Validación de contraseñas y emails

2. ✅ Sistema de auditoría completo:
   - Tabla `auditoria` en base de datos (`database/auditoria.sql`)
   - Modelo, DAO y Servicio de auditoría
   - Controlador y vista para consulta de registros
   - Filtros avanzados (usuario, acción, tabla, fechas)
   - Estadísticas de acciones realizadas
   - Integración automática en todos los controladores

3. ✅ Sistema de respaldo y restauración de base de datos:
   - Clase `util/Respaldo.php` con respaldo automático y manual
   - Controlador y vista para gestión de respaldos
   - Restauración de respaldos con validación
   - Sistema de metadatos para cada respaldo
   - Protección del directorio de respaldos

4. ✅ Integración de seguridad en controladores existentes:
   - Mejora de `LoginController.php` con auditoría y seguridad
   - Mejora de `UsuarioController.php` con auditoría y validaciones
   - Actualización de `index.php` con sesiones seguras
   - Actualización de `router.php` con nuevas rutas protegidas

5. ✅ Mejoras en interfaz de usuario:
   - Dashboard actualizado con enlaces a nuevos módulos
   - Vista de auditoría con filtros y estadísticas
   - Vista de respaldos con gestión completa
   - Página de error 404 personalizada

---

### ARCHIVOS CREADOS/MODIFICADOS POR EL GRUPO 6

**Archivos Nuevos Creados:**
- `util/Seguridad.php` - Clase de utilidades de seguridad
- `model/Auditoria.php` - Modelo de auditoría
- `model/AuditoriaDAO.php` - DAO de auditoría
- `service/AuditoriaService.php` - Servicio de auditoría
- `util/Respaldo.php` - Clase de respaldo de base de datos
- `controller/AuditoriaController.php` - Controlador de auditoría
- `controller/RespaldoController.php` - Controlador de respaldo
- `view/vAuditoria.php` - Vista de auditoría
- `view/vRespaldo.php` - Vista de respaldos
- `database/auditoria.sql` - Script SQL para crear tabla de auditoría
- `view/errores/404.php` - Página de error 404
- `backups/.htaccess` - Protección del directorio de respaldos
- `backups/.gitkeep` - Mantener estructura de directorio

**Archivos Modificados:**
- `index.php` - Integración de sesiones seguras
- `router.php` - Agregado de rutas de auditoría y respaldo
- `controller/LoginController.php` - Integración de seguridad y auditoría
- `controller/UsuarioController.php` - Integración de auditoría y validaciones
- `view/dashboard.php` - Actualización con nuevos módulos

### NOTAS ADICIONALES

Este módulo fue desarrollado siguiendo las mejores prácticas de seguridad web, incluyendo protección contra los principales vectores de ataque como XSS, CSRF, SQL Injection (mediante prepared statements), y fuerza bruta. El sistema de auditoría permite rastrear todas las acciones realizadas en el sistema, mientras que el sistema de respaldo garantiza la recuperación de datos en caso de pérdida de información.

**⚠️ IMPORTANTE PARA COLABORADORES:**
Todos los colaboradores deben ejecutar el script `database/auditoria.sql` en su base de datos antes de continuar trabajando, ya que se ha agregado una nueva tabla necesaria para el funcionamiento del sistema de auditoría.

---

**Firma del Representante del Grupo:**

_____________________________  
[Nombre del Representante]  
Fecha: _______________

