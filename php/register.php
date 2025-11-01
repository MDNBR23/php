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
$name = sanitizeInput($data['name'] ?? '');
$email = sanitizeInput($data['email'] ?? '');
$phone = sanitizeInput($data['phone'] ?? '');
$institucion = sanitizeInput($data['institucion'] ?? '');
$cat = sanitizeInput($data['cat'] ?? '');

if (empty($username) || empty($password) || empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben completarse']);
    exit;
}

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya existe']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, name, email, phone, institucion, cat, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'user', 'pendiente')");
$stmt->bind_param("sssssss", $username, $hashedPassword, $name, $email, $phone, $institucion, $cat);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Registro exitoso. Tu cuenta está pendiente de aprobación por un administrador.'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar usuario']);
}

$stmt->close();
$conn->close();
?>
