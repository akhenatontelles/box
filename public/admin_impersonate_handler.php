<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/User.php';

if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header("location: login.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $targetUserId = (int)$_GET['user_id'];

    $userModel = new User();
    // Precisamos de um método para buscar um usuário pelo ID. Vou adicioná-lo.
    // Por enquanto, vamos assumir que ele existe.
    $targetUser = $userModel->findUserById($targetUserId);

    if ($targetUser) {
        // Salvar a sessão original do admin, se ainda não estiver salva
        if (!isset($_SESSION['admin_id'])) {
            $_SESSION['admin_id'] = $_SESSION['user_id'];
            $_SESSION['admin_name'] = $_SESSION['user_name'];
            $_SESSION['admin_role'] = $_SESSION['user_role'];
        }

        // Personificar o usuário alvo
        $_SESSION['user_id'] = $targetUser->id;
        $_SESSION['user_email'] = $targetUser->email;
        $_SESSION['user_name'] = $targetUser->username;
        $_SESSION['user_role'] = $targetUser->role;

        header("location: dashboard.php");
        exit();
    }
}

header("location: admin.php?error=usernotfound");
exit();
