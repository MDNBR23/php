<?php
require_once 'config.php';

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'active' => false,
    'message' => 'El sitio está funcionando normalmente.'
]);
?>
