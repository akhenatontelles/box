<?php
require_once '../app/config.php';
require_once '../app/models/User.php';
require_once '../app/core/session_helper.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'login' => trim($_POST['login']), // Campo agora é 'login'
        'password' => trim($_POST['password']),
    ];

    if(empty($data['login']) || empty($data['password'])){
        header("location: login.php?error=emptyfields");
        exit();
    }

    $userModel = new User();

    // Usar o novo método para encontrar o usuário
    if($loggedInUser = $userModel->findUserByEmailOrUsername($data['login'])){
        $hashed_password = $loggedInUser->password_hash;
        if(password_verify($data['password'], $hashed_password)){
            createUserSession($loggedInUser);
        } else {
            header("location: login.php?error=wrongcredentials");
            exit();
        }
    } else {
        header("location: login.php?error=wrongcredentials");
        exit();
    }

} else {
    header("location: login.php");
    exit();
}

function createUserSession($user){
    // Inicia a sessão se ainda não estiver iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->username;
    $_SESSION['user_role'] = $user->role;
    header("location: dashboard.php");
    exit();
}
