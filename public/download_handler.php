<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/File.php';

// Proteger a página
if(!isLoggedIn()){
    header("location: login.php");
    exit();
}

if(isset($_GET['file_id'])){
    $fileId = (int)$_GET['file_id'];
    $userId = $_SESSION['user_id'];

    $fileModel = new File();
    $file = $fileModel->getFileById($fileId, $userId);

    if($file){
        // O arquivo pertence ao usuário, prossiga com o download
        $filePath = UPLOAD_PATH . '/' . $userId . '/' . $file->server_filename;

        if(file_exists($filePath)){
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $file->mime_type);
            header('Content-Disposition: attachment; filename="' . basename($file->name) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            // Limpar o buffer de saída
            ob_clean();
            flush();

            readfile($filePath);
            exit;
        } else {
            die('Arquivo não encontrado no servidor.');
        }
    } else {
        // Tentativa de acesso a arquivo de outro usuário ou arquivo inexistente
        die('Acesso negado ou arquivo não encontrado.');
    }
} else {
    header("location: dashboard.php");
    exit();
}
