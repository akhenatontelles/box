<?php
/*
 * Roteador Principal da Aplicação
 * Cria a URL e carrega o controller principal
 * Formato da URL - /controller/method/params
 */
class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        $url = $this->getUrl();

        // Procura em controllers pelo primeiro valor da URL
        if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
            // Se existir, define como controller atual
            $this->currentController = ucwords($url[0]);
            // Unset 0 Index
            unset($url[0]);
        }

        // Requere o controller
        require_once '../app/controllers/'. $this->currentController . '.php';

        // Instancia o controller
        $this->currentController = new $this->currentController;

        // Checa pela segunda parte da url (o método)
        if(isset($url[1])){
            // Checa se o método existe no controller
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }

        // Pega os parâmetros
        $this->params = $url ? array_values($url) : [];

        // Chama o método do controller com um array de parâmetros
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
