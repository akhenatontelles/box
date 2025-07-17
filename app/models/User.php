<?php
require_once '../app/core/Database.php';

class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Encontrar usuário por email
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Checar se o email existe
        if($this->db->rowCount() > 0){
            return $row;
        } else {
            return false;
        }
    }

    // Registrar novo usuário
    public function register($data){
        $this->db->query('INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)');
        // Bind dos valores
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        // Executar
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Obter todos os usuários (para admin)
    public function getAllUsers(){
        $this->db->query('SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
}
