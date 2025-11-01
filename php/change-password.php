<?php
require_once 'config.php';

header('Content-Type: application/json');

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$currentPassword = $data['currentPassword'] ?? '';
$newPassword = $data['newPassword'] ?? '';

if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
    exit;
}

$conn = getDBConnection();

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (!password_verify($currentPassword, $row['password'])) {
        echo json_encode(['success' => false, 'message' => 'Contraseña actual incorrecta']);
        $stmt->close();
        $conn->close();
        exit;
    }
    
    $stmt->close();
    
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashedPassword, $username);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Contraseña actualizada exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar contraseña']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}

$conn->close();
?>
