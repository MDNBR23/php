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

if (empty($token)) {
    echo json_encode(['success' => false, 'message' => 'Token requerido']);
    exit;
}

$conn = getDBConnection();

// Verificar que el token existe, no ha sido usado y no ha expirado
$stmt = $conn->prepare("
    SELECT email, expires_at 
    FROM password_reset_tokens 
    WHERE token = ? 
    AND used = FALSE 
    AND expires_at > NOW()
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tokenData = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'valid' => true,
        'email' => $tokenData['email']
    ]);
} else {
    echo json_encode([
        'success' => true,
        'valid' => false,
        'message' => 'Token inválido o expirado'
    ]);
}

$stmt->close();
$conn->close();
?>
