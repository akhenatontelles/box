<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/File.php';

if (!isLoggedIn()) {
    header("location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = (int)$_POST['item_id'];
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $userId = $_SESSION['user_id'];

    $redirectUrl = "dashboard.php" . ($parentId ? "?folder=$parentId" : "");

    $fileModel = new File();

    // Primeiro, pegar as informações do item para saber se é um arquivo
    $itemToDelete = $fileModel->getFileById($itemId, $userId);

    if ($itemToDelete) {
        // Se for um arquivo, deletar o arquivo físico
        if ($itemToDelete->type == 'file') {
            $filePath = UPLOAD_PATH . '/' . $userId . '/' . $itemToDelete->server_filename;
            if (file_exists($filePath)) {
                unlink($filePath); // Deleta o arquivo do servidor
            }
        }
        // OBS: A deleção de pastas com conteúdo físico não está implementada de forma recursiva aqui.
        // O banco de dados irá deletar os registros em cascata.

        // Deletar o registro do banco de dados
        if (!$fileModel->deleteFile($itemId, $userId)) {
             error_log("Erro ao excluir o item $itemId do banco de dados.");
        }
    }

    header("Location: $redirectUrl");
    exit();
} else {
    header("location: dashboard.php");
    exit();
}
