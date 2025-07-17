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
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $userId = $_SESSION['user_id'];

    $uploadDir = UPLOAD_PATH . '/' . $userId . '/';
    // Criar diretório do usuário se não existir
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileModel = new File();

    $files = $_FILES['filesToUpload'];
    $fileCount = count($files['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = $files['name'][$i];
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileType = $files['type'][$i];

            // Gerar um nome de arquivo único no servidor
            $serverFileName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $filePath = $uploadDir . $serverFileName;

            if (move_uploaded_file($fileTmpName, $filePath)) {
                // Arquivo movido com sucesso, agora insere no DB
                $data = [
                    'user_id' => $userId,
                    'parent_id' => $parentId,
                    'name' => $fileName,
                    'path' => $filePath, // Caminho completo no servidor
                    'size' => $fileSize,
                    'mime_type' => $fileType,
                    'server_filename' => $serverFileName
                ];

                if (!$fileModel->createFile($data)) {
                    // Lidar com erro de inserção no DB
                    // Talvez deletar o arquivo que foi upado?
                    error_log("Erro ao inserir o arquivo {$fileName} no banco de dados.");
                }
            } else {
                // Lidar com erro no upload
                error_log("Erro ao mover o arquivo {$fileName} para {$filePath}.");
            }
        }
    }

    // Redirecionar de volta para o dashboard
    $redirectUrl = "dashboard.php";
    if ($parentId) {
        $redirectUrl .= "?folder=" . $parentId;
    }
    header("Location: " . $redirectUrl);
    exit();

} else {
    header("location: dashboard.php");
    exit();
}
