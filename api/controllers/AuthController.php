<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';

class AuthController {
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        $username = $data['username'];
        $password = $data['password'];

        $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

        $user = new User($db);

        if ($user->authenticate($username, $password)) {
            // In a real application, you would generate a JWT or session token here.
            // For now, we'll just return a success message.
            http_response_code(200);
            echo json_encode(['message' => 'Login successful']);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }
}
