<?php
// Iniciar a sessão
session_start();

// Função para verificar se o usuário está logado
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}
