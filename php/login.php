<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = sanitizeInput($data['username'] ?? '');
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son requeridos']);
    exit;
}

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();

if ($user['status'] !== 'aprobado') {
    echo json_encode(['success' => false, 'message' => 'Usuario pendiente de aprobación']);
    $stmt->close();
    $conn->close();
    exit;
}

if (password_verify($password, $user['password'])) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['avatar'] = $user['avatar'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'user' => [
            'username' => $user['username'],
            'name' => $user['name'],
            'role' => $user['role'],
            'avatar' => $user['avatar']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
}

$stmt->close();
$conn->close();
?>
