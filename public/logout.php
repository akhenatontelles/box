<?php
require_once '../app/core/session_helper.php';

// Destruir a sessão
unset($_SESSION['user_id']);
unset($_SESSION['user_email']);
unset($_SESSION['user_name']);
unset($_SESSION['user_role']);
session_destroy();

// Redirecionar para a página de login
header("location: login.php");
exit();
