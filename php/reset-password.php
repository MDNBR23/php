<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$token = sanitizeInput($data['token'] ?? '');
$newPassword = $data['newPassword'] ?? '';

if (empty($token) || empty($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Token y contraseña son requeridos']);
    exit;
}

$conn = getDBConnection();

// Verificar que el token existe, no ha sido usado y no ha expirado
$stmt = $conn->prepare("
    SELECT email 
    FROM password_reset_tokens 
    WHERE token = ? 
    AND used = FALSE 
    AND expires_at > NOW()
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Token inválido o expirado. Solicita un nuevo enlace de recuperación.']);
    $stmt->close();
    $conn->close();
    exit;
}

$tokenData = $result->fetch_assoc();
$email = $tokenData['email'];
$stmt->close();

// Actualizar la contraseña del usuario
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashedPassword, $email);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

// Marcar el token como usado
$stmt = $conn->prepare("UPDATE password_reset_tokens SET used = TRUE WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->close();

// Limpiar tokens expirados (limpieza automática)
$conn->query("DELETE FROM password_reset_tokens WHERE expires_at < NOW() OR (used = TRUE AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY))");

echo json_encode([
    'success' => true,
    'message' => 'Contraseña actualizada exitosamente. Ya puedes iniciar sesión con tu nueva contraseña.'
]);

$conn->close();
?>
