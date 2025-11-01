<?php
require_once 'config.php';
require_once 'email-config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = sanitizeInput($data['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'El email es requerido']);
    exit;
}

$conn = getDBConnection();

// Verificar que el email existe
$stmt = $conn->prepare("SELECT username, name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No se encontró una cuenta con ese email']);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Generar token único
$token = bin2hex(random_bytes(32));

// Expiración del token: 1 hora desde ahora
$expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Guardar token en la base de datos
$stmt = $conn->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $token, $expiresAt);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error al generar token de recuperación']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

// Construir URL de reset
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$resetUrl = "$protocol://$host/reset-password.php?token=$token";

// Preparar el email
$subject = 'Recuperación de contraseña - Med Tools Hub';

$htmlBody = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #008B8B, #008080); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 15px 30px; background: #008B8B; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔐 Recuperación de Contraseña</h1>
            <p>Med Tools Hub</p>
        </div>
        <div class='content'>
            <p>Hola <strong>{$user['name']}</strong>,</p>
            
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en Med Tools Hub.</p>
            
            <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
            
            <p style='text-align: center;'>
                <a href='$resetUrl' class='button'>Restablecer Contraseña</a>
            </p>
            
            <p>O copia y pega este enlace en tu navegador:</p>
            <p style='word-break: break-all; background: white; padding: 10px; border-radius: 5px;'>$resetUrl</p>
            
            <div class='warning'>
                <strong>⚠️ Importante:</strong>
                <ul>
                    <li>Este enlace expirará en <strong>1 hora</strong></li>
                    <li>Si no solicitaste este cambio, ignora este email</li>
                    <li>Nunca compartas este enlace con nadie</li>
                </ul>
            </div>
        </div>
        <div class='footer'>
            <p>Este es un email automático de Med Tools Hub</p>
            <p>Si tienes problemas, contacta al administrador</p>
            <p>&copy; " . date('Y') . " Med Tools Hub - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
";

$textBody = "
Hola {$user['name']},

Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en Med Tools Hub.

Para restablecer tu contraseña, copia y pega el siguiente enlace en tu navegador:

$resetUrl

IMPORTANTE:
- Este enlace expirará en 1 hora
- Si no solicitaste este cambio, ignora este email
- Nunca compartas este enlace con nadie

Med Tools Hub
";

// Intentar enviar el email
$result = sendEmail($email, $subject, $htmlBody);

if ($result['success']) {
    echo json_encode([
        'success' => true,
        'message' => 'Se ha enviado un email con instrucciones para restablecer tu contraseña. Revisa tu bandeja de entrada.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al enviar el email. Por favor, contacta al administrador.'
    ]);
}

$conn->close();
?>
