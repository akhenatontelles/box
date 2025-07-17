<?php
require_once '../app/config.php';
require_once '../app/models/User.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitize POST data
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'username' => trim($_POST['username']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
    ];

    // Validação básica (pode ser melhorada)
    if(empty($data['username']) || empty($data['email']) || empty($data['password'])){
        // Lidar com erro - redirecionar de volta com mensagem
        header("location: register.php?error=emptyfields");
        exit();
    }

    // Hash da senha
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // Instanciar o modelo User
    $userModel = new User();

    // Registrar usuário
    if($userModel->register($data)){
        // Redirecionar para o login
        header("location: login.php?success=registered");
        exit();
    } else {
        die('Algo deu errado.');
    }

} else {
    // Se não for POST, redirecionar para a página de registro
    header("location: register.php");
    exit();
}
