<?php
require_once 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        requireAuth();
        getAnnouncements();
        break;
    case 'POST':
        requireAdmin();
        addAnnouncement();
        break;
    case 'PUT':
        requireAdmin();
        updateAnnouncement();
        break;
    case 'DELETE':
        requireAdmin();
        deleteAnnouncement();
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function getAnnouncements() {
    $conn = getDBConnection();
    
    $result = $conn->query("SELECT * FROM announcements WHERE is_global = TRUE ORDER BY fecha DESC");
    
    $announcements = [];
    while ($row = $result->fetch_assoc()) {
        $announcements[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo'],
            'fecha' => $row['fecha'],
            'texto' => $row['texto'],
            'img' => $row['img'],
            'global' => (bool)$row['is_global']
        ];
    }
    
    echo json_encode(['success' => true, 'announcements' => $announcements]);
    $conn->close();
}

function addAnnouncement() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = generateUUID();
    $titulo = sanitizeInput($data['titulo'] ?? '');
    $fecha = sanitizeInput($data['fecha'] ?? date('Y-m-d'));
    $texto = sanitizeInput($data['texto'] ?? '');
    $img = sanitizeInput($data['img'] ?? '');
    
    if (empty($titulo)) {
        echo json_encode(['success' => false, 'message' => 'El título es requerido']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("INSERT INTO announcements (id, titulo, fecha, texto, img, is_global) VALUES (?, ?, ?, ?, ?, TRUE)");
    $stmt->bind_param("sssss", $id, $titulo, $fecha, $texto, $img);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Anuncio agregado', 'id' => $id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar anuncio']);
    }
    
    $stmt->close();
    $conn->close();
}

function updateAnnouncement() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = sanitizeInput($data['id'] ?? '');
    $titulo = sanitizeInput($data['titulo'] ?? '');
    $fecha = sanitizeInput($data['fecha'] ?? '');
    $texto = sanitizeInput($data['texto'] ?? '');
    $img = sanitizeInput($data['img'] ?? '');
    
    if (empty($id) || empty($titulo)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("UPDATE announcements SET titulo = ?, fecha = ?, texto = ?, img = ? WHERE id = ?");
    $stmt->bind_param("sssss", $titulo, $fecha, $texto, $img, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Anuncio actualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar anuncio']);
    }
    
    $stmt->close();
    $conn->close();
}

function deleteAnnouncement() {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = sanitizeInput($data['id'] ?? '');
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no especificado']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Anuncio eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar anuncio']);
    }
    
    $stmt->close();
    $conn->close();
}
?>
