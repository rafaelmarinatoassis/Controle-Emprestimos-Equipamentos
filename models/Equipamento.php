<?php

require_once __DIR__ . '/../config/Database.php';

class Equipamento {
    private $db;
    private $id;
    private $nome;
    private $categoria;
    private $numero_patrimonio;
    private $estado_conservacao;
    private $status;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function cadastrar($nome, $categoria, $numero_patrimonio, $estado_conservacao) {
        try {
            $sql = "INSERT INTO equipamentos (nome, categoria, numero_patrimonio, estado_conservacao) 
                    VALUES (:nome, :categoria, :patrimonio, :estado)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':patrimonio', $numero_patrimonio);
            $stmt->bindParam(':estado', $estado_conservacao);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
            return false;
        }
    }

    public function listar($pagina = 1, $itens_por_pagina = 10, $termo_busca = '') {
        try {
            $offset = ($pagina - 1) * $itens_por_pagina;
            
            $sql = "SELECT * FROM equipamentos";
            $params = [];
            
            if ($termo_busca) {
                $sql .= " WHERE nome LIKE :termo_busca 
                         OR categoria LIKE :termo_busca 
                         OR numero_patrimonio LIKE :termo_busca";
                $params[':termo_busca'] = "%{$termo_busca}%";
            }
            
            $sql .= " ORDER BY nome LIMIT :limit OFFSET :offset";
            
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
            $sql = "SELECT COUNT(*) as total FROM equipamentos";
            
            if ($termo_busca) {
                $sql .= " WHERE nome LIKE :termo_busca 
                         OR categoria LIKE :termo_busca 
                         OR numero_patrimonio LIKE :termo_busca";
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

    public function contarTotalEquipamentos() {
        try {
            $sql = "SELECT COUNT(*) as total FROM equipamentos";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch(PDOException $e) {
            echo "Erro ao contar total de equipamentos: " . $e->getMessage();
            return 0;
        }
    }

    public function contarDisponiveis() {
        try {
            $sql = "SELECT COUNT(*) as total FROM equipamentos WHERE status = 'disponivel'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch(PDOException $e) {
            echo "Erro ao contar equipamentos disponíveis: " . $e->getMessage();
            return 0;
        }
    }

    public function buscarPorId($id) {
        try {
            error_log("Buscando equipamento por ID: " . $id);
            
            $sql = "SELECT * FROM equipamentos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Resultado da busca por ID:");
            error_log(print_r($resultado, true));
            
            return $resultado;
        } catch(PDOException $e) {
            error_log("Erro ao buscar equipamento por ID: " . $e->getMessage());
            return null;
        }
    }

    public function atualizarStatus($id, $status) {
        try {
            $sql = "UPDATE equipamentos SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erro ao atualizar status: " . $e->getMessage();
            return false;
        }
    }

    public function atualizar($id, $nome, $categoria, $numero_patrimonio, $estado_conservacao) {
        try {
            $sql = "UPDATE equipamentos SET 
                    nome = :nome,
                    categoria = :categoria,
                    numero_patrimonio = :numero_patrimonio,
                    estado_conservacao = :estado_conservacao
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':numero_patrimonio', $numero_patrimonio);
            $stmt->bindParam(':estado_conservacao', $estado_conservacao);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarDisponiveis() {
        try {
            $sql = "SELECT * FROM equipamentos WHERE status = 'disponivel' ORDER BY nome";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Log dos equipamentos disponíveis
            error_log("Equipamentos disponíveis encontrados: " . print_r($resultados, true));
            
            return $resultados;
        } catch(PDOException $e) {
            error_log("Erro ao listar equipamentos disponíveis: " . $e->getMessage());
            return [];
        }
    }
} 