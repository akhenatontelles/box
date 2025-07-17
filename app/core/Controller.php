<?php
/*
 * Controller Base
 * Carrega os modelos e as views
 */
class Controller {
    // Carregar modelo
    public function model($model){
        // Requere o arquivo do modelo
        require_once '../app/models/' . $model . '.php';
        // Instancia o modelo
        return new $model();
    }

    // Carregar view
    public function view($view, $data = []){
        // Checa se o arquivo da view existe
        if(file_exists('../app/views/' . $view . '.php')){
            require_once '../app/views/' . $view . '.php';
        } else {
            // A view não existe
            die('View não encontrada');
        }
    }
}
