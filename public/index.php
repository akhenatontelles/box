<?php
require_once '../app/config.php';
require_once '../app/core/Database.php';

echo '<h1>' . SITE_NAME . '</h1>';
echo '<p>Bem-vindo ao nosso sistema!</p>';

// Testando a conexão com o banco de dados
$db = new Database();
if ($db) {
    echo '<p>Conexão com o banco de dados estabelecida com sucesso!</p>';
} else {
    echo '<p>Erro ao conectar ao banco de dados.</p>';
}

// Aqui virá o roteamento para os controladores
