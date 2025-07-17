<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/User.php';

// Apenas admins podem executar este script
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header("location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'username' => trim($_POST['username']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'role' => trim($_POST['role']),
    ];

    // Validação
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        header("location: admin.php?error=emptyfields");
        exit();
    }
    if ($data['role'] !== 'user' && $data['role'] !== 'admin') {
        header("location: admin.php?error=invalidrole");
        exit();
    }

    // Hash da senha
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    $userModel = new User();
    if ($userModel->register($data)) {
        header("location: admin.php?success=usercreated");
        exit();
    } else {
        die('Algo deu errado ao criar o usuário.');
    }

} else {
    header("location: admin.php");
    exit();
}
