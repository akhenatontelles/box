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

    if($file && $file->type == 'file'){
        // O arquivo pertence ao usuário, prossiga com a visualização
        $filePath = UPLOAD_PATH . '/' . $userId . '/' . $file->server_filename;

        if(file_exists($filePath)){
            // Definir o tipo de conteúdo (MIME type)
            header('Content-Type: ' . $file->mime_type);
            // Definir como 'inline' para o navegador tentar exibir o arquivo
            header('Content-Disposition: inline; filename="' . basename($file->name) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            // Limpar o buffer de saída
            ob_clean();
            flush();

            // Ler e enviar o arquivo
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            die('Arquivo não encontrado no servidor.');
        }
    } else {
        // Tentativa de acesso a arquivo de outro usuário, pasta ou arquivo inexistente
        http_response_code(403);
        die('Acesso negado ou item inválido.');
    }
} else {
    header("location: dashboard.php");
    exit();
}
