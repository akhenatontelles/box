<?php
class Users extends Controller {
    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function login(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Processar formulário
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'login' => trim($_POST['login']),
                'password' => trim($_POST['password']),
            ];

            if($loggedInUser = $this->userModel->findUserByEmailOrUsername($data['login'])){
                if(password_verify($data['password'], $loggedInUser->password_hash)){
                    $this->createUserSession($loggedInUser);
                } else {
                    $this->view('users/login', ['error' => 'Credenciais inválidas']);
                }
            } else {
                $this->view('users/login', ['error' => 'Usuário não encontrado']);
            }
        } else {
            // Carregar formulário
            $this->view('users/login');
        }
    }

    public function register(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Processar formulário
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role' => 'user'
            ];

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if($this->userModel->register($data)){
                // Idealmente, redirecionar com uma mensagem flash
                header('Location: ' . URL_ROOT . '/users/login');
            } else {
                die('Algo deu errado.');
            }

        } else {
            // Carregar formulário
            $this->view('users/register');
        }
    }

    public function createUserSession($user){
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->username;
        $_SESSION['user_role'] = $user->role;
        header('Location: ' . URL_ROOT . '/pages/dashboard');
    }

    public function logout(){
        session_start();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        header('Location: ' . URL_ROOT . '/users/login');
    }

    public function deleteUser($id){
        session_start();
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
            header('Location: ' . URL_ROOT);
            exit;
        }

        if($this->userModel->deleteUser($id)){
            // Deletar a pasta de uploads do usuário
            $userUploadDir = UPLOAD_PATH . '/' . $id;
            if(is_dir($userUploadDir)){
                // Função recursiva para deletar a pasta e seu conteúdo
                $this->deleteDirectoryRecursive($userUploadDir);
            }
            header('Location: ' . URL_ROOT . '/pages/admin');
        } else {
            die('Algo deu errado ao deletar o usuário.');
        }
    }

    private function deleteDirectoryRecursive($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!$this->deleteDirectoryRecursive($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
}
