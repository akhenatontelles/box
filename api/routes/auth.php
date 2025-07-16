<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$authController = new AuthController();

$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && $request_uri === '/api/login') {
    $authController->login();
}
