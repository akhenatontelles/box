<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log básico para verificar se o script inicia
error_log("upload_handler.php iniciado.");

require_once '../app/config.php';
error_log("config.php carregado.");

require_once '../app/core/session_helper.php';
error_log("session_helper.php carregado.");

require_once '../app/models/File.php';
error_log("File.php carregado.");


// Proteger a página
if(!isLoggedIn()){
    error_log("Usuário não logado. Redirecionando.");
    header("location: login.php");
    exit();
}
error_log("Usuário logado: " . $_SESSION['user_id']);


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $userId = $_SESSION['user_id'];

    // UPLOAD_PATH é definido em config.php
    // Ex: /path/to/your/project/uploads
    $uploadDir = rtrim(UPLOAD_PATH, '/') . '/' . $userId . '/';
    error_log("Diretório de upload: " . $uploadDir);

    // Criar diretório do usuário se não existir
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log("Falha ao criar o diretório: " . $uploadDir);
            die("Erro crítico: Não foi possível criar o diretório de upload.");
        }
    }

    $fileModel = new File();
    $files = $_FILES['filesToUpload'];

    if (empty($files) || $files['error'][0] == UPLOAD_ERR_NO_FILE) {
        error_log("Nenhum arquivo enviado.");
        header("Location: dashboard.php" . ($parentId ? "?folder=$parentId" : ""));
        exit();
    }

    $fileCount = count($files['name']);
    error_log("Número de arquivos para upload: " . $fileCount);

    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = basename($files['name'][$i]); // Segurança básica
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileType = mime_content_type($fileTmpName); // Mais seguro que o do browser

            $serverFileName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $filePath = $uploadDir . $serverFileName;

            if (move_uploaded_file($fileTmpName, $filePath)) {
                error_log("Arquivo movido com sucesso para: " . $filePath);
                $data = [
                    'user_id' => $userId,
                    'parent_id' => $parentId,
                    'name' => $fileName,
                    'path' => $filePath,
                    'size' => $fileSize,
                    'mime_type' => $fileType,
                    'server_filename' => $serverFileName
                ];

                if (!$fileModel->createFile($data)) {
                    error_log("Erro ao inserir o arquivo {$fileName} no banco de dados.");
                    unlink($filePath); // Remove o arquivo órfão
                }
            } else {
                error_log("Erro ao mover o arquivo {$fileName}. Código de erro: " . $files['error'][$i]);
            }
        } else {
            error_log("Erro de upload do arquivo " . $files['name'][$i] . ". Código: " . $files['error'][$i]);
        }
    }

    $redirectUrl = "dashboard.php" . ($parentId ? "?folder=$parentId" : "");
    header("Location: " . $redirectUrl);
    exit();

} else {
    header("location: dashboard.php");
    exit();
}
