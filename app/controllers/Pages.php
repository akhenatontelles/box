<?php
class Pages extends Controller {
    public function __construct(){
        // Carregar modelos se necessário
    }

    // O método padrão
    public function index(){
        // Por padrão, redireciona para o login se não estiver logado,
        // ou para o dashboard se estiver.
        session_start();
        if(isset($_SESSION['user_id'])){
            header('Location: ' . URL_ROOT . '/pages/dashboard');
        } else {
            header('Location: ' . URL_ROOT . '/users/login');
        }
    }

    public function dashboard(){
        session_start();
        if(!isset($_SESSION['user_id'])){
            header('Location: ' . URL_ROOT . '/users/login');
            exit;
        }

        $fileModel = $this->model('File');
        $userId = $_SESSION['user_id'];
        // A lógica de obter a pasta atual virá da URL, ex: /pages/dashboard/FOLDER_ID
        $current_folder_id = null; // Simplificado por enquanto

        $data = [
            'items' => $fileModel->getFilesByUserIdAndParent($userId, $current_folder_id),
            'breadcrumbs' => $fileModel->getFolderPath($current_folder_id, $userId),
            'current_folder_id' => $current_folder_id
        ];
        $this->view('pages/dashboard', $data);
    }

    // Outros métodos para admin, search, etc. virão aqui
}
