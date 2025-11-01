<?php
require_once 'config.php';

header('Content-Type: application/json');

requireAuth();

$conn = getDBConnection();

$result = $conn->query("SELECT COUNT(*) as count FROM suggestions WHERE respondida = FALSE");
$row = $result->fetch_assoc();

echo json_encode(['success' => true, 'count' => (int)$row['count']]);
$conn->close();
?>
