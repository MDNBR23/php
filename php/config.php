<?php
// Configuración de la base de datos
// IMPORTANTE: Actualiza estos valores con los de tu hosting

define('DB_HOST', 'localhost'); // Normalmente 'localhost' en hosting compartido
define('DB_USER', 'medtools_admin'); // Tu usuario de MySQL
define('DB_PASS', 'SMC~(Ds~z;6o'); // Tu contraseña de MySQL
define('DB_NAME', 'medtools_hub'); // El nombre de tu base de datos

// Crear conexión
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die(json_encode([
            'success' => false,
            'message' => 'Error de conexión: ' . $conn->connect_error
        ]));
    }
    
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Iniciar sesión
session_start();

// Función para verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['username']);
}

// Función para verificar si es administrador
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Función para requerir autenticación
function requireAuth() {
    if (!isAuthenticated()) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'No autenticado'
        ]);
        exit;
    }
}

// Función para requerir rol de administrador
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'No autorizado'
        ]);
        exit;
    }
}

// Función para generar UUID
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Función para sanitizar entrada
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
