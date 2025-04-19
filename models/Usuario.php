<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private $db;
    private $id;
    private $nome_completo;
    private $tipo;
    private $matricula;
    private $email;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function cadastrar($nome_completo, $tipo, $matricula, $email) {
        try {
            $sql = "INSERT INTO usuarios (nome_completo, tipo, matricula, email) 
                    VALUES (:nome, :tipo, :matricula, :email)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $nome_completo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
            return false;
        }
    }

    public function atualizar($id, $nome_completo, $tipo, $matricula, $email) {
        try {
            $sql = "UPDATE usuarios SET 
                    nome_completo = :nome,
                    tipo = :tipo,
                    matricula = :matricula,
                    email = :email
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $nome_completo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erro ao atualizar: " . $e->getMessage();
            return false;
        }
    }

    public function listar($pagina = 1, $itens_por_pagina = 10, $termo_busca = '') {
        try {
            $offset = ($pagina - 1) * $itens_por_pagina;
            
            $sql = "SELECT * FROM usuarios";
            $params = [];
            
            if ($termo_busca) {
                $sql .= " WHERE nome_completo LIKE :termo_busca";
                $params[':termo_busca'] = "%{$termo_busca}%";
            }
            
            $sql .= " ORDER BY nome_completo LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            
            if ($termo_busca) {
                $stmt->bindValue(':termo_busca', "%{$termo_busca}%", PDO::PARAM_STR);
            }
            
            $stmt->bindValue(':limit', $itens_por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erro ao listar: " . $e->getMessage();
            return [];
        }
    }

    public function contarTotal($termo_busca = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios";
            $params = [];
            
            if ($termo_busca) {
                $sql .= " WHERE nome_completo LIKE :termo_busca";
                $params[':termo_busca'] = "%{$termo_busca}%";
            }
            
            $stmt = $this->db->prepare($sql);
            
            if ($termo_busca) {
                $stmt->bindValue(':termo_busca', "%{$termo_busca}%", PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch(PDOException $e) {
            echo "Erro ao contar total: " . $e->getMessage();
            return 0;
        }
    }

    public function contarAtivos() {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch(PDOException $e) {
            echo "Erro ao contar usuários ativos: " . $e->getMessage();
            return 0;
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erro ao buscar: " . $e->getMessage();
            return null;
        }
    }

    public function buscarParaAutocomplete($termo = '') {
        try {
            $sql = "SELECT id, nome_completo, matricula FROM usuarios 
                    WHERE nome_completo LIKE :termo 
                    OR matricula LIKE :termo 
                    ORDER BY nome_completo 
                    LIMIT 10";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':termo', "%{$termo}%", PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
} 