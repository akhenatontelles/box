<?php
// Carrega o arquivo de configuração
require_once 'config.php';

// Autoloader para as bibliotecas do core
spl_autoload_register(function($className){
    require_once 'core/' . $className . '.php';
});
