<?php
require_once '../app/config.php';
require_once '../app/models/User.php';
require_once '../app/core/session_helper.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitize POST data
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
    ];

    // Validação
    if(empty($data['email']) || empty($data['password'])){
        header("location: login.php?error=emptyfields");
        exit();
    }

    // Instanciar o modelo User
    $userModel = new User();

    // Encontrar usuário por email
    if($loggedInUser = $userModel->findUserByEmail($data['email'])){
        // Usuário encontrado, verificar senha
        $hashed_password = $loggedInUser->password_hash;
        if(password_verify($data['password'], $hashed_password)){
            // Senha correta, criar sessão
            createUserSession($loggedInUser);
        } else {
            // Senha incorreta
            header("location: login.php?error=wrongpassword");
            exit();
        }
    } else {
        // Usuário não encontrado
        header("location: login.php?error=nouser");
        exit();
    }

} else {
    header("location: login.php");
    exit();
}


function createUserSession($user){
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->username;
    $_SESSION['user_role'] = $user->role;
    header("location: dashboard.php"); // Redirecionar para a página principal
}
