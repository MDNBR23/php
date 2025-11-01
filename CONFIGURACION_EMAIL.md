# 📧 Guía de Configuración de Email SMTP para Med Tools Hub

Esta guía te ayudará a configurar el envío de emails desde tu aplicación Med Tools Hub para la funcionalidad de **recuperación de contraseñas**.

---

## 🎯 ¿Para qué necesito configurar email?

Con el email configurado, tus usuarios podrán:
- ✅ Recuperar sus contraseñas si las olvidan
- ✅ Recibir un enlace seguro para restablecer su contraseña
- ✅ Tener una experiencia más profesional y autónoma

---

## 📋 Paso 1: Crear una cuenta de email en cPanel

### 1.1. Acceder a Cuentas de Email

1. Entra a tu **cPanel**
2. Busca la sección **"Email"**
3. Haz clic en **"Cuentas de email"**

### 1.2. Crear nueva cuenta de email

1. Haz clic en **"Crear"** o **"+ Crear"**
2. Completa los datos:
   - **Email:** `noreply@tudominio.com` (puedes usar otro nombre)
   - **Contraseña:** Genera una contraseña segura (guárdala, la necesitarás)
   - **Cuota de buzón:** 250 MB es suficiente
3. Haz clic en **"Crear cuenta"**

✅ **Ejemplo:**
```
Email: noreply@medtoolshub.com
Contraseña: MiContraseñaSegura123!
```

---

## 📧 Paso 2: Obtener configuración SMTP de tu hosting

### 2.1. Información que necesitas:

Normalmente, la configuración SMTP de tu hosting es:

| Campo | Valor típico | Tu valor |
|-------|--------------|----------|
| **Servidor SMTP** | `mail.tudominio.com` | ___________ |
| **Puerto** | `587` (TLS) o `465` (SSL) | ___________ |
| **Usuario** | `noreply@tudominio.com` | ___________ |
| **Contraseña** | Tu contraseña del email | ___________ |
| **Encriptación** | `TLS` o `SSL` | ___________ |

### 2.2. ¿Dónde encontrar esta información?

**Opción A:** En cPanel
1. Ve a **Email → Cuentas de email**
2. Busca tu cuenta y haz clic en **"Conectar dispositivos"**
3. Verás la configuración SMTP completa

**Opción B:** Contacta a tu proveedor de hosting
- Pregunta: *"¿Cuál es la configuración SMTP para enviar emails desde mi aplicación PHP?"*

**Configuración común según proveedor:**

- **Hostinger:** `smtp.hostinger.com`, Puerto: `587`, TLS
- **GoDaddy:** `relay-hosting.secureserver.net`, Puerto: `25` o `587`
- **Hosting genérico:** `mail.tudominio.com`, Puerto: `587`, TLS

---

## ⚙️ Paso 3: Configurar en la aplicación

### 3.1. Editar el archivo de configuración

1. Abre el archivo `php/email-config.php` en tu editor de texto
2. Busca estas líneas (aproximadamente líneas 8-15):

```php
define('SMTP_HOST', 'mail.tudominio.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'noreply@tudominio.com');
define('SMTP_PASS', 'tu_contraseña_email');
define('SMTP_FROM_EMAIL', 'noreply@tudominio.com');
define('SMTP_FROM_NAME', 'Med Tools Hub');
```

### 3.2. Actualizar con tus datos

Reemplaza con la información de tu email:

```php
// === CONFIGURACIÓN REAL - EJEMPLO ===
define('SMTP_HOST', 'mail.medtoolshub.com'); // Tu servidor SMTP
define('SMTP_PORT', 587); // Puerto SMTP (587 para TLS, 465 para SSL)
define('SMTP_USER', 'noreply@medtoolshub.com'); // Tu email completo
define('SMTP_PASS', 'MiContraseñaSegura123!'); // Contraseña del email
define('SMTP_FROM_EMAIL', 'noreply@medtoolshub.com'); // Email del remitente
define('SMTP_FROM_NAME', 'Med Tools Hub'); // Nombre que aparecerá
```

### 3.3. Ajustar encriptación (si es necesario)

Si tu hosting usa SSL en lugar de TLS:

```php
define('SMTP_PORT', 465); // Cambiar a 465
define('SMTP_ENCRYPTION', 'ssl'); // Cambiar a 'ssl'
```

### 3.4. Guardar y subir

1. Guarda el archivo `php/email-config.php`
2. Súbelo a tu hosting (reemplaza el anterior)
3. Verifica que esté en: `public_html/php/email-config.php`

---

## 🧪 Paso 4: Probar la funcionalidad

### 4.1. Prueba de recuperación de contraseña

1. Ve a tu sitio: `https://tudominio.com/index.php`
2. Haz clic en **"¿Olvidaste tu contraseña?"**
3. Ingresa un email de usuario registrado
4. Haz clic en **"Solicitar Código"**

### 4.2. ¿Qué debería pasar?

✅ **Si funciona correctamente:**
- Verás un mensaje: *"Se ha enviado un email con instrucciones..."*
- Recibirás un email en la bandeja de entrada del usuario
- El email contendrá un enlace seguro para restablecer la contraseña

❌ **Si no funciona:**
- Verás un mensaje de error
- Ve a la sección de **Solución de problemas** más abajo

---

## 🔧 Solución de problemas comunes

### Error: "Error al enviar email"

**Posibles causas y soluciones:**

#### 1. Credenciales incorrectas
```
❌ Problema: Usuario o contraseña incorrectos
✅ Solución: Verifica que SMTP_USER y SMTP_PASS sean correctos
```

#### 2. Puerto bloqueado
```
❌ Problema: El puerto 587 o 465 está bloqueado
✅ Solución: Contacta a tu hosting para verificar qué puertos están abiertos
✅ Alternativa: Intenta con puerto 25 (menos seguro)
```

#### 3. Servidor SMTP incorrecto
```
❌ Problema: El servidor SMTP no es el correcto
✅ Solución: Verifica en cPanel cuál es el servidor SMTP correcto
✅ Común: mail.tudominio.com o smtp.tudominio.com
```

#### 4. Encriptación incorrecta
```
❌ Problema: Estás usando TLS pero debería ser SSL (o viceversa)
✅ Solución: Intenta cambiar entre 'tls' y 'ssl'
```

#### 5. Límite de envío
```
❌ Problema: Tu hosting tiene límite de emails por hora
✅ Solución: Espera un tiempo y vuelve a intentar
✅ Verifica: Contacta a tu hosting para conocer los límites
```

---

## 📝 Notas de seguridad

### ⚠️ IMPORTANTE:

1. **Nunca compartas tu contraseña de email**
   - No la publiques en foros o repositorios públicos

2. **Usa una contraseña fuerte**
   - Mínimo 12 caracteres
   - Combina letras, números y símbolos

3. **Protege el archivo email-config.php**
   - Asegúrate de que tenga permisos 644
   - No debe ser accesible directamente desde el navegador

4. **Monitorea el uso**
   - Revisa periódicamente cuántos emails se envían
   - Detecta posibles abusos

---

## 🎨 Personalización del email

Si quieres personalizar el aspecto del email de recuperación:

1. Abre `php/reset-password-request.php`
2. Busca la variable `$htmlBody` (alrededor de la línea 50)
3. Modifica el HTML a tu gusto
4. Puedes cambiar:
   - Colores
   - Logo (agrega una imagen)
   - Texto del mensaje
   - Estilo del botón

**Ejemplo de cambio de color:**
```php
// Cambiar el color del header
.header { background: linear-gradient(135deg, #FF6B6B, #FF8E53); /* Rojo/naranja */ }
```

---

## 📊 Alternativa simple (función mail() de PHP)

Si tienes problemas con SMTP, puedes usar la función `mail()` nativa de PHP:

La aplicación ya incluye esta funcionalidad alternativa. Si SMTP no funciona, automáticamente intentará usar `mail()`.

**Ventajas:**
- ✅ Más simple, no requiere configuración SMTP
- ✅ Funciona en la mayoría de hostings

**Desventajas:**
- ❌ Menos confiable
- ❌ Puede caer en spam más fácilmente
- ❌ No todos los hostings lo permiten

---

## ✅ Checklist de verificación

Antes de dar por terminada la configuración, verifica:

- [ ] Cuenta de email creada en cPanel
- [ ] Archivo `email-config.php` actualizado con tus credenciales
- [ ] Archivo subido al servidor
- [ ] Tabla `password_reset_tokens` existe en la base de datos
- [ ] Prueba de envío de email realizada
- [ ] Email recibido correctamente
- [ ] Enlace de reset funciona y cambia la contraseña

---

## 🆘 ¿Aún tienes problemas?

Si después de seguir esta guía sigues teniendo problemas:

1. **Revisa los logs de error de PHP:**
   - En cPanel → Errores → Ver últimos errores
   - Busca mensajes relacionados con "mail" o "SMTP"

2. **Contacta a tu hosting:**
   - Pregunta: *"¿Permiten el envío de emails desde aplicaciones PHP?"*
   - Pregunta: *"¿Cuál es la configuración SMTP correcta?"*

3. **Prueba con un script simple:**
   Crea un archivo `test-email.php`:
   ```php
   <?php
   $to = 'tu-email@gmail.com';
   $subject = 'Prueba de email';
   $message = 'Este es un email de prueba';
   $headers = 'From: noreply@tudominio.com';
   
   if (mail($to, $subject, $message, $headers)) {
       echo 'Email enviado';
   } else {
       echo 'Error al enviar';
   }
   ?>
   ```

---

**¡Listo! Tu sistema de recuperación de contraseñas debería estar funcionando.** 🎉
