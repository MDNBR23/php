<?php
require_once 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        requireAuth();
        getSuggestions();
        break;
    case 'POST':
        requireAuth();
        addSuggestion();
        break;
    case 'PUT':
        requireAdmin();
        updateSuggestion();
        break;
    case 'DELETE':
        requireAdmin();
        deleteSuggestion();
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function getSuggestions() {
    $conn = getDBConnection();
    
    $result = $conn->query("SELECT * FROM suggestions ORDER BY fecha DESC");
    
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'mensaje' => $row['mensaje'],
            'respuesta' => $row['respuesta'],
            'fecha' => $row['fecha'],
            'respondida' => (bool)$row['respondida'],
            'fechaRespuesta' => $row['fecha_respuesta']
        ];
    }
    
    echo json_encode(['success' => true, 'suggestions' => $suggestions]);
    $conn->close();
}

function addSuggestion() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = generateUUID();
    $username = $_SESSION['username'];
    $mensaje = sanitizeInput($data['mensaje'] ?? '');
    $fecha = date('Y-m-d H:i:s');
    
    if (empty($mensaje)) {
        echo json_encode(['success' => false, 'message' => 'El mensaje es requerido']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("INSERT INTO suggestions (id, username, mensaje, fecha, respondida) VALUES (?, ?, ?, ?, FALSE)");
    $stmt->bind_param("ssss", $id, $username, $mensaje, $fecha);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sugerencia enviada', 'id' => $id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al enviar sugerencia']);
    }
    
    $stmt->close();
    $conn->close();
}

function updateSuggestion() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = sanitizeInput($data['id'] ?? '');
    $respuesta = sanitizeInput($data['respuesta'] ?? '');
    $fechaRespuesta = date('Y-m-d H:i:s');
    
    if (empty($id) || empty($respuesta)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("UPDATE suggestions SET respuesta = ?, respondida = TRUE, fecha_respuesta = ? WHERE id = ?");
    $stmt->bind_param("sss", $respuesta, $fechaRespuesta, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Respuesta guardada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar respuesta']);
    }
    
    $stmt->close();
    $conn->close();
}

function deleteSuggestion() {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = sanitizeInput($data['id'] ?? '');
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no especificado']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("DELETE FROM suggestions WHERE id = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sugerencia eliminada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar sugerencia']);
    }
    
    $stmt->close();
    $conn->close();
}
?>
