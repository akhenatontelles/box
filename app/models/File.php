<?php
require_once '../app/core/Database.php';

class File {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Obter arquivos e pastas de um usuário em um diretório específico
    public function getFilesByUserIdAndParent($userId, $parentId = null){
        $sql = 'SELECT * FROM files WHERE user_id = :user_id AND parent_id ';
        if (is_null($parentId)) {
            $sql .= 'IS NULL';
        } else {
            $sql .= '= :parent_id';
        }
        $sql .= ' ORDER BY type DESC, name ASC'; // Pastas primeiro, depois arquivos, ordenados por nome

        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        if (!is_null($parentId)) {
            $this->db->bind(':parent_id', $parentId);
        }

        $results = $this->db->resultSet();
        return $results;
    }

    // Obter informações de um arquivo/pasta pelo ID
    public function getFileById($id, $userId){
        $this->db->query('SELECT * FROM files WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    // Obter o caminho (breadcrumbs) de uma pasta
    public function getFolderPath($folderId, $userId){
        $path = [];
        $currentFolderId = $folderId;

        while($currentFolderId !== null){
            $folder = $this->getFileById($currentFolderId, $userId);
            if($folder){
                array_unshift($path, $folder);
                $currentFolderId = $folder->parent_id;
            } else {
                break;
            }
        }
        return $path;
    }

    // Criar nova pasta
    public function createFolder($userId, $parentId, $folderName){
        $this->db->query(
            'INSERT INTO files (user_id, parent_id, name, type)
             VALUES (:user_id, :parent_id, :name, "folder")'
        );
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':parent_id', $parentId, $parentId ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $this->db->bind(':name', $folderName);

        return $this->db->execute();
    }

    // Adicionar novo arquivo
    public function createFile($data){
        $this->db->query(
            'INSERT INTO files (user_id, parent_id, name, path, size, mime_type, type, server_filename)
             VALUES (:user_id, :parent_id, :name, :path, :size, :mime_type, "file", :server_filename)'
        );
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':parent_id', $data['parent_id'], $data['parent_id'] ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':path', $data['path']);
        $this->db->bind(':size', $data['size']);
        $this->db->bind(':mime_type', $data['mime_type']);
        $this->db->bind(':server_filename', $data['server_filename']);

        return $this->db->execute();
    }

    // Renomear arquivo/pasta
    public function renameFile($id, $userId, $newName){
        $this->db->query('UPDATE files SET name = :name WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':name', $newName);
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Excluir arquivo/pasta
    public function deleteFile($id, $userId){
        $this->db->query('DELETE FROM files WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Buscar arquivos/pastas por nome
    public function searchFiles($userId, $query){
        $this->db->query('SELECT * FROM files WHERE user_id = :user_id AND name LIKE :query ORDER BY type DESC, name ASC');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':query', '%' . $query . '%');
        return $this->db->resultSet();
    }
}
