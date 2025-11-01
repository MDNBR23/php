<?php
// Configuración de email para Med Tools Hub
// IMPORTANTE: Actualiza estos valores con tu configuración de email

// === PASO 1: Crea una cuenta de email en cPanel ===
// Ve a cPanel → Email → Cuentas de email
// Crea una cuenta, por ejemplo: noreply@tudominio.com

// === PASO 2: Configura estos valores ===
define('EMAIL_FROM_EMAIL', 'noreply@tudominio.com'); // Tu email completo
define('EMAIL_FROM_NAME', 'Med Tools Hub'); // Nombre que aparecerá en los emails

// Función para enviar emails usando la función mail() nativa de PHP
// Esta función funciona en la mayoría de hostings compartidos sin configuración adicional
function sendEmail($to, $subject, $htmlBody) {
    // Configurar headers del email
    $headers = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . EMAIL_FROM_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    // Intentar enviar el email
    $result = @mail($to, $subject, $htmlBody, $headers);
    
    if ($result) {
        return ['success' => true, 'message' => 'Email enviado exitosamente'];
    } else {
        // Si mail() falla, puede ser porque el hosting requiere configuración adicional
        return ['success' => false, 'message' => 'Error al enviar email. Contacta al administrador.'];
    }
}

// NOTA: Si la función mail() no funciona en tu hosting:
// 1. Verifica que tu hosting permita envío de emails desde PHP
// 2. Algunos hostings requieren usar un servidor SMTP específico
// 3. Contacta a tu proveedor de hosting para obtener instrucciones
// 4. Considera usar un servicio de email externo como SendGrid, Mailgun, etc.
?>
