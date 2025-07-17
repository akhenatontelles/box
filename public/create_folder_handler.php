<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/File.php';

// Proteger a página
if(!isLoggedIn()){
    header("location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $folderName = trim($_POST['folderName']);
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $userId = $_SESSION['user_id'];

    if(!empty($folderName)){
        $fileModel = new File();
        if($fileModel->createFolder($userId, $parentId, $folderName)){
            // Sucesso
            header("location: dashboard.php?folder=" . $parentId);
            exit();
        } else {
            // Erro
            die('Erro ao criar a pasta.');
        }
    } else {
        // Nome da pasta vazio
        header("location: dashboard.php?folder=" . $parentId . "&error=emptyname");
        exit();
    }

} else {
    header("location: dashboard.php");
    exit();
}
