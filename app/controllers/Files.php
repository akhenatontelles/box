<?php
class Files extends Controller {
    public function __construct(){
        $this->fileModel = $this->model('File');
        session_start();
        if(!isset($_SESSION['user_id'])){
            header('Location: ' . URL_ROOT . '/users/login');
            exit;
        }
    }

    public function upload(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // ... (Lógica do upload_handler.php)
            // Após o processo, redirecionar de volta
            header('Location: ' . URL_ROOT . '/pages/dashboard/' . $parentId);
        }
    }

    public function rename(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // ... (Lógica do rename_handler.php)
            // Após o processo, redirecionar de volta
            header('Location: ' . URL_ROOT . '/pages/dashboard/' . $parentId);
        }
    }

    public function delete(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // ... (Lógica do delete_handler.php)
            // Após o processo, redirecionar de volta
            header('Location: ' . URL_ROOT . '/pages/dashboard/' . $parentId);
        }
    }

    public function download($id){
        // ... (Lógica do download_handler.php)
    }

    public function preview($id){
        // ... (Lógica do preview_handler.php)
    }
}
