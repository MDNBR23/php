<?php
require_once 'config.php';

header('Content-Type: application/json');

requireAuth();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $conn = getDBConnection();
    
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT username, name, email, phone, institucion, cat, avatar FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'user' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
    
    $stmt->close();
    $conn->close();
} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $username = $_SESSION['username'];
    $name = sanitizeInput($data['name'] ?? '');
    $email = sanitizeInput($data['email'] ?? '');
    $phone = sanitizeInput($data['phone'] ?? '');
    $institucion = sanitizeInput($data['institucion'] ?? '');
    $cat = sanitizeInput($data['cat'] ?? '');
    $avatar = $data['avatar'] ?? '';
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, institucion = ?, cat = ?, avatar = ? WHERE username = ?");
    $stmt->bind_param("sssssss", $name, $email, $phone, $institucion, $cat, $avatar, $username);
    
    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['avatar'] = $avatar;
        
        echo json_encode(['success' => true, 'message' => 'Perfil actualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar perfil']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
