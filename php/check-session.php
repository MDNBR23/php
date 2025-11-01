<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isAuthenticated()) {
    echo json_encode([
        'success' => true,
        'authenticated' => true,
        'user' => [
            'username' => $_SESSION['username'],
            'name' => $_SESSION['name'],
            'role' => $_SESSION['role'],
            'email' => $_SESSION['email'],
            'avatar' => $_SESSION['avatar'] ?? ''
        ]
    ]);
} else {
    echo json_encode([
        'success' => true,
        'authenticated' => false
    ]);
}
?>
