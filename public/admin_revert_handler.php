<?php
require_once '../app/core/session_helper.php';

// Apenas admins que estão personificando podem reverter
if (!isLoggedIn() || !isset($_SESSION['admin_id'])) {
    header("location: dashboard.php");
    exit();
}

// Restaurar a sessão original do admin
$_SESSION['user_id'] = $_SESSION['admin_id'];
$_SESSION['user_name'] = $_SESSION['admin_name'];
$_SESSION['user_role'] = $_SESSION['admin_role'];
// O email pode ser buscado novamente ou salvo na sessão original
// Para simplificar, vamos buscá-lo novamente se necessário ou ignorar por enquanto
unset($_SESSION['user_email']); // Forçar a atualização se necessário

// Limpar as variáveis de sessão da personificação
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_role']);

// Redirecionar de volta para o painel de administração
header("location: admin.php");
exit();
