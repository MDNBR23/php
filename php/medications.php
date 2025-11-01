<?php
require_once 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        requireAuth();
        getMedications();
        break;
    case 'POST':
        requireAdmin();
        addMedication();
        break;
    case 'PUT':
        requireAdmin();
        updateMedication();
        break;
    case 'DELETE':
        requireAdmin();
        deleteMedication();
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function getMedications() {
    $conn = getDBConnection();
    
    $result = $conn->query("SELECT * FROM medications ORDER BY nombre ASC");
    
    $medications = [];
    while ($row = $result->fetch_assoc()) {
        $medications[] = $row;
    }
    
    echo json_encode(['success' => true, 'medications' => $medications]);
    $conn->close();
}

function addMedication() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = generateUUID();
    $nombre = sanitizeInput($data['nombre'] ?? '');
    $grupo = sanitizeInput($data['grupo'] ?? '');
    $dilucion = sanitizeInput($data['dilucion'] ?? '');
    $comentarios = sanitizeInput($data['comentarios'] ?? '');
    
    if (empty($nombre) || empty($grupo)) {
        echo json_encode(['success' => false, 'message' => 'Nombre y grupo son requeridos']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("INSERT INTO medications (id, nombre, grupo, dilucion, comentarios) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $id, $nombre, $grupo, $dilucion, $comentarios);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Medicamento agregado', 'id' => $id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar medicamento']);
    }
    
    $stmt->close();
    $conn->close();
}

function updateMedication() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = sanitizeInput($data['id'] ?? '');
    $nombre = sanitizeInput($data['nombre'] ?? '');
    $grupo = sanitizeInput($data['grupo'] ?? '');
    $dilucion = sanitizeInput($data['dilucion'] ?? '');
    $comentarios = sanitizeInput($data['comentarios'] ?? '');
    
    if (empty($id) || empty($nombre) || empty($grupo)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("UPDATE medications SET nombre = ?, grupo = ?, dilucion = ?, comentarios = ? WHERE id = ?");
    $stmt->bind_param("sssss", $nombre, $grupo, $dilucion, $comentarios, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Medicamento actualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar medicamento']);
    }
    
    $stmt->close();
    $conn->close();
}

function deleteMedication() {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = sanitizeInput($data['id'] ?? '');
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no especificado']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("DELETE FROM medications WHERE id = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Medicamento eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar medicamento']);
    }
    
    $stmt->close();
    $conn->close();
}
?>
