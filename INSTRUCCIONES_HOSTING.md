# Guía completa para subir Med Tools Hub a tu hosting

## 📋 Requisitos previos

Tu hosting debe tener:
- ✅ PHP 7.4 o superior
- ✅ MySQL 5.7 o superior
- ✅ Acceso a cPanel o phpMyAdmin
- ✅ Soporte para archivos .htaccess (Apache)

## 📁 Paso 1: Preparar los archivos

### Archivos que DEBES subir a tu hosting:

```
/public_html/  (o tu carpeta raíz)
├── php/
│   ├── config.php
│   ├── email-config.php (configurar si quieres recuperación de contraseña)
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── check-session.php
│   ├── users.php
│   ├── medications.php
│   ├── announcements.php
│   ├── guides.php
│   ├── suggestions.php
│   ├── suggestions-count.php
│   ├── maintenance.php
│   ├── profile.php
│   ├── change-password.php
│   ├── reset-password-request.php
│   ├── reset-password.php
│   └── verify-reset-token.php
├── index.php
├── register.php
├── reset-password.php
├── main.php
├── vademecum.php
├── herramientas.php
├── sugerencias.php
├── configuracion.php
├── admin.php
├── script.js
├── style.css
└── database.sql (solo para importar)
```

### Archivos que NO debes subir:
- ❌ server.js
- ❌ package.json
- ❌ package-lock.json
- ❌ node_modules/
- ❌ data/ (carpeta JSON)
- ❌ Archivos .html originales

## 🗄️ Paso 2: Configurar la base de datos MySQL

### 2.1. Crear la base de datos

1. Entra a **cPanel** de tu hosting
2. Busca **"MySQL Databases"** o **"Bases de datos MySQL"**
3. Crea una nueva base de datos:
   - Nombre: `medtools_hub` (o el que prefieras)
4. Crea un nuevo usuario:
   - Usuario: `medtools_user` (o el que prefieras)
   - Contraseña: (genera una contraseña segura)
5. Asigna el usuario a la base de datos con **TODOS los privilegios**

### 2.2. Importar el esquema de la base de datos

1. Abre **phpMyAdmin** desde cPanel
2. Selecciona tu base de datos `medtools_hub`
3. Ve a la pestaña **"Importar"**
4. Selecciona el archivo `database.sql`
5. Haz clic en **"Continuar"**

✅ Esto creará todas las tablas necesarias e insertará:
   - Usuario admin (usuario: `admin`, contraseña: `1234`)
   - 16 medicamentos de ejemplo
   - 1 anuncio de bienvenida
   - 1 guía de ejemplo

## ⚙️ Paso 3: Configurar la conexión a la base de datos

1. Abre el archivo `php/config.php` en tu editor de texto
2. Actualiza las credenciales de tu base de datos:

```php
define('DB_HOST', 'localhost'); // Generalmente es 'localhost'
define('DB_USER', 'medtools_user'); // Tu usuario MySQL
define('DB_PASS', 'tu_contraseña_segura'); // Tu contraseña MySQL
define('DB_NAME', 'medtools_hub'); // Tu base de datos
```

3. **IMPORTANTE**: En algunos hostings compartidos, el formato es:
   - Usuario: `tudominio_medtools_user`
   - Base de datos: `tudominio_medtools_hub`

## 📤 Paso 4: Subir archivos al hosting

### Opción A: Usando FileZilla (FTP)

1. Descarga **FileZilla Client** (gratuito)
2. Conéctate a tu hosting usando:
   - Host: `ftp.tudominio.com`
   - Usuario: Tu usuario FTP
   - Contraseña: Tu contraseña FTP
   - Puerto: 21
3. Sube todos los archivos a la carpeta `public_html/` (o la carpeta raíz de tu dominio)

### Opción B: Usando el Administrador de Archivos de cPanel

1. En cPanel, abre **"Administrador de archivos"**
2. Navega a `public_html/`
3. Haz clic en **"Cargar"**
4. Selecciona y sube todos los archivos y carpetas

## 🔧 Paso 5: Configurar permisos

1. En el Administrador de archivos o FTP:
2. Asigna permisos **755** a la carpeta `php/`
3. Asigna permisos **644** a todos los archivos `.php`

## 📧 Paso 6: Configurar Email SMTP (OPCIONAL pero recomendado)

**¿Para qué sirve?** Permite que los usuarios recuperen sus contraseñas olvidadas por email.

### Opción A: Configuración rápida

1. Crea una cuenta de email en cPanel (ej: `noreply@tudominio.com`)
2. Edita el archivo `php/email-config.php`
3. Actualiza estas líneas con tus datos:

```php
define('SMTP_HOST', 'mail.tudominio.com'); // Tu servidor SMTP
define('SMTP_PORT', 587); // Puerto (587 para TLS)
define('SMTP_USER', 'noreply@tudominio.com'); // Tu email
define('SMTP_PASS', 'tu_contraseña_email'); // Contraseña del email
```

4. Guarda y sube el archivo

### Opción B: Guía completa paso a paso

👉 **Consulta el archivo `CONFIGURACION_EMAIL.md`** para instrucciones detalladas sobre:
- Cómo crear la cuenta de email en cPanel
- Obtener la configuración SMTP de tu hosting
- Configurar y probar el envío de emails
- Solucionar problemas comunes

⚠️ **Si no configuras el email:**
- Los usuarios NO podrán recuperar sus contraseñas
- El administrador tendrá que cambiar las contraseñas manualmente

## 🧪 Paso 7: Probar la aplicación

1. Abre tu navegador y visita: `https://tudominio.com/index.php`
2. Prueba iniciar sesión con:
   - **Usuario**: `admin`
   - **Contraseña**: `1234`

### ✅ Si todo funciona correctamente:
- Deberías ver la página de login
- Poder iniciar sesión
- Ver la página principal con anuncios y guías
- Acceder al vademecum de medicamentos

### ❌ Si hay errores:

#### Error: "Cannot connect to database"
- Verifica las credenciales en `php/config.php`
- Asegúrate de que el usuario tenga todos los privilegios
- Contacta a tu proveedor de hosting si el problema persiste

#### Error: "Page not found" o "404"
- Verifica que los archivos están en la carpeta correcta
- Algunos hostings requieren que los archivos estén en `public_html/`

#### Error: "Internal Server Error" o "500"
- Verifica los permisos de los archivos
- Revisa los logs de error en cPanel → "Errores"
- Asegúrate de que tu hosting soporta PHP 7.4+

## 🔐 Paso 8: Seguridad inicial (IMPORTANTE)

### ⚠️ Cambiar la contraseña del administrador

1. Inicia sesión como `admin` / `1234`
2. Ve a **Configuración**
3. Cambia la contraseña inmediatamente

### 🗑️ Eliminar archivos innecesarios

Después de subir todo y verificar que funciona:
- Elimina `database.sql` del servidor (ya no lo necesitas)
- Elimina `INSTRUCCIONES_HOSTING.md` del servidor

## 📧 Probar recuperación de contraseña (si configuraste email)

1. Ve a `https://tudominio.com/index.php`
2. Haz clic en "¿Olvidaste tu contraseña?"
3. Ingresa el email del admin
4. Deberías recibir un email con un enlace para restablecer la contraseña

## 📊 Datos de ejemplo

La base de datos incluye:
- **16 medicamentos** del vademecum neonatal
- **1 usuario admin** (cambiar contraseña)
- **1 anuncio** de bienvenida
- **1 guía** clínica de ejemplo

Puedes:
- Agregar más medicamentos desde el panel de administración
- Eliminar o editar el anuncio y guía de ejemplo
- Crear nuevos usuarios desde el formulario de registro

## 🎯 Próximos pasos

1. **Personalizar contenido**:
   - Actualizar anuncios
   - Agregar guías clínicas
   - Completar el vademecum de medicamentos

2. **Registrar usuarios**:
   - Los usuarios se registran desde `register.php`
   - Quedan en estado "pendiente"
   - El admin debe aprobarlos desde el panel de Administración

3. **Configurar SSL/HTTPS**:
   - La mayoría de hostings ofrecen SSL gratuito
   - En cPanel: "SSL/TLS" → "Let's Encrypt"
   - Esto hace que tu sitio sea seguro (https://)

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs de error de PHP en cPanel
2. Verifica que las credenciales de MySQL son correctas
3. Contacta al soporte de tu hosting si es un problema del servidor

---

## 📝 Diferencias con la versión Node.js

### ✅ Ventajas de PHP:
- No necesita mantener un proceso corriendo
- Funciona en hosting compartido económico
- Configuración más simple
- Compatible con la mayoría de hostings

### ⚠️ Cambios respecto a la versión Node.js:
- **PostgreSQL → MySQL**: Ahora usa MySQL (más común en hostings compartidos)
- **Envío de emails**: Requiere configuración SMTP (ver `CONFIGURACION_EMAIL.md`)
  - Sin configuración: El admin puede cambiar contraseñas manualmente

### 🔄 Funcionalidades mantenidas:
- ✅ Sistema de login/registro
- ✅ Gestión de usuarios (aprobación de admin)
- ✅ Vademecum de medicamentos
- ✅ Anuncios y guías
- ✅ Sistema de sugerencias
- ✅ Herramientas médicas
- ✅ Perfil de usuario con avatar
- ✅ Cambio de contraseña
- ✅ Modo oscuro/claro
- ✅ Diseño responsive (móvil y escritorio)

---

**¡Listo! Tu aplicación Med Tools Hub debería estar funcionando en tu hosting.** 🎉
