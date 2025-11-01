<?php
require_once 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        requireAdmin();
        getUsers();
        break;
    case 'PUT':
        requireAdmin();
        updateUserStatus();
        break;
    case 'DELETE':
        requireAdmin();
        deleteUser();
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function getUsers() {
    $conn = getDBConnection();
    
    $result = $conn->query("SELECT id, username, name, email, phone, institucion, role, status, cat, avatar, created_at FROM users ORDER BY created_at DESC");
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    echo json_encode(['success' => true, 'users' => $users]);
    $conn->close();
}

function updateUserStatus() {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = sanitizeInput($data['username'] ?? '');
    $status = sanitizeInput($data['status'] ?? '');
    
    if (empty($username) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE username = ?");
    $stmt->bind_param("ss", $status, $username);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Estado actualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
    }
    
    $stmt->close();
    $conn->close();
}

function deleteUser() {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = sanitizeInput($data['username'] ?? '');
    
    if (empty($username)) {
        echo json_encode(['success' => false, 'message' => 'Usuario no especificado']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
    }
    
    $stmt->close();
    $conn->close();
}
?>
