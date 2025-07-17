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
    $newName = trim($_POST['newItemName']);
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $userId = $_SESSION['user_id'];

    $redirectUrl = "dashboard.php" . ($parentId ? "?folder=$parentId" : "");

    if (!empty($newName)) {
        $fileModel = new File();
        if ($fileModel->renameFile($itemId, $userId, $newName)) {
            // Sucesso
        } else {
            // Erro
            error_log("Erro ao renomear o item $itemId.");
        }
    }
    header("Location: $redirectUrl");
    exit();
} else {
    header("location: dashboard.php");
    exit();
}
