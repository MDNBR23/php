<?php
require_once 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        requireAuth();
        getGuides();
        break;
    case 'POST':
        requireAdmin();
        addGuide();
        break;
    case 'PUT':
        requireAdmin();
        updateGuide();
        break;
    case 'DELETE':
        requireAdmin();
        deleteGuide();
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function getGuides() {
    $conn = getDBConnection();
    
    $result = $conn->query("SELECT * FROM guides WHERE is_global = TRUE ORDER BY fecha DESC");
    
    $guides = [];
    while ($row = $result->fetch_assoc()) {
        $guides[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo'],
            'fecha' => $row['fecha'],
            'texto' => $row['texto'],
            'url' => $row['url'],
            'global' => (bool)$row['is_global']
        ];
    }
    
    echo json_encode(['success' => true, 'guides' => $guides]);
    $conn->close();
}

function addGuide() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = generateUUID();
    $titulo = sanitizeInput($data['titulo'] ?? '');
    $fecha = sanitizeInput($data['fecha'] ?? date('Y-m-d'));
    $texto = sanitizeInput($data['texto'] ?? '');
    $url = sanitizeInput($data['url'] ?? '');
    
    if (empty($titulo)) {
        echo json_encode(['success' => false, 'message' => 'El título es requerido']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("INSERT INTO guides (id, titulo, fecha, texto, url, is_global) VALUES (?, ?, ?, ?, ?, TRUE)");
    $stmt->bind_param("sssss", $id, $titulo, $fecha, $texto, $url);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Guía agregada', 'id' => $id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar guía']);
    }
    
    $stmt->close();
    $conn->close();
}

function updateGuide() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = sanitizeInput($data['id'] ?? '');
    $titulo = sanitizeInput($data['titulo'] ?? '');
    $fecha = sanitizeInput($data['fecha'] ?? '');
    $texto = sanitizeInput($data['texto'] ?? '');
    $url = sanitizeInput($data['url'] ?? '');
    
    if (empty($id) || empty($titulo)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("UPDATE guides SET titulo = ?, fecha = ?, texto = ?, url = ? WHERE id = ?");
    $stmt->bind_param("sssss", $titulo, $fecha, $texto, $url, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Guía actualizada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar guía']);
    }
    
    $stmt->close();
    $conn->close();
}

function deleteGuide() {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = sanitizeInput($data['id'] ?? '');
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no especificado']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("DELETE FROM guides WHERE id = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Guía eliminada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar guía']);
    }
    
    $stmt->close();
    $conn->close();
}
?>
