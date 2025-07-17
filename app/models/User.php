<?php
require_once '../app/core/Database.php';

class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Encontrar usuário por email ou username
    public function findUserByEmailOrUsername($login){
        $this->db->query('SELECT * FROM users WHERE email = :login OR username = :login');
        $this->db->bind(':login', $login);

        $row = $this->db->single();

        // Checar se o usuário existe
        if($this->db->rowCount() > 0){
            return $row;
        } else {
            return false;
        }
    }

    // Registrar novo usuário (com role)
    public function register($data){
        $this->db->query('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password, :role)');
        // Bind dos valores
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

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

    // Encontrar usuário por ID
    public function findUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    // Deletar usuário
    public function deleteUser($id){
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}
