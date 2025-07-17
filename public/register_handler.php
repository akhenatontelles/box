<?php
require_once '../app/config.php';
require_once '../app/models/User.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'username' => trim($_POST['username']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'role' => 'user' // Forçar a role 'user' para registros públicos
    ];

    if(empty($data['username']) || empty($data['email']) || empty($data['password'])){
        header("location: register.php?error=emptyfields");
        exit();
    }

    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    $userModel = new User();

    if($userModel->register($data)){
        header("location: login.php?success=registered");
        exit();
    } else {
        die('Algo deu errado.');
    }

} else {
    header("location: register.php");
    exit();
}
