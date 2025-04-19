<?php

require_once __DIR__ . '/../config/Database.php';

class Emprestimo {
    private $db;
    private $id;
    private $equipamento_id;
    private $usuario_id;
    private $data_emprestimo;
    private $data_prevista_devolucao;
    private $data_devolucao;
    private $status;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function devolver($id) {
        try {
            $this->db->beginTransaction();

            // Atualiza o empréstimo
            $sql = "UPDATE emprestimos SET status = 'devolvido', data_devolucao = CURRENT_DATE 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Busca o equipamento_id do empréstimo
            $sql = "SELECT equipamento_id FROM emprestimos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);

            // Atualiza o status do equipamento
            $sql = "UPDATE equipamentos SET status = 'disponivel' WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $emprestimo['equipamento_id']);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch(PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao devolver equipamento: " . $e->getMessage());
            return false;
        }
    }

    public function listar($pagina = 1, $itens_por_pagina = 10, $termo_busca = '') {
        try {
            $offset = ($pagina - 1) * $itens_por_pagina;
            
            $sql = "SELECT e.*, 
                           eq.nome as equipamento_nome,
                           eq.categoria as equipamento_categoria,
                           eq.numero_patrimonio as equipamento_patrimonio,
                           eq.estado_conservacao as equipamento_estado,
                           u.nome_completo as usuario_nome 
                    FROM emprestimos e 
                    JOIN equipamentos eq ON e.equipamento_id = eq.id 
                    JOIN usuarios u ON e.usuario_id = u.id";
            
            if ($termo_busca) {
                $sql .= " WHERE eq.nome LIKE :termo_busca 
                         OR u.nome_completo LIKE :termo_busca 
                         OR u.matricula LIKE :termo_busca";
            }
            
            $sql .= " ORDER BY e.data_emprestimo DESC 
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            
            if ($termo_busca) {
                $termo_busca = "%{$termo_busca}%";
                $stmt->bindParam(':termo_busca', $termo_busca);
            }
            
            $stmt->bindValue(':limit', $itens_por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro ao listar: " . $e->getMessage());
            return [];
        }
    }

    public function contarTotal($termo_busca = '') {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM emprestimos e 
                    JOIN equipamentos eq ON e.equipamento_id = eq.id 
                    JOIN usuarios u ON e.usuario_id = u.id";
            
            if ($termo_busca) {
                $sql .= " WHERE eq.nome LIKE :termo_busca 
                         OR u.nome_completo LIKE :termo_busca 
                         OR u.matricula LIKE :termo_busca";
            }
            
            $stmt = $this->db->prepare($sql);
            
            if ($termo_busca) {
                $termo_busca = "%{$termo_busca}%";
                $stmt->bindParam(':termo_busca', $termo_busca);
            }
            
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch(PDOException $e) {
            error_log("Erro ao contar total: " . $e->getMessage());
            return 0;
        }
    }

    public function contarAtivos() {
        try {
            $sql = "SELECT COUNT(*) as total FROM emprestimos WHERE status = 'ativo'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch(PDOException $e) {
            error_log("Erro ao contar empréstimos ativos: " . $e->getMessage());
            return 0;
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT e.*, u.nome_completo as usuario_nome, eq.nome as equipamento_nome 
                    FROM emprestimos e 
                    JOIN usuarios u ON e.usuario_id = u.id 
                    JOIN equipamentos eq ON e.equipamento_id = eq.id 
                    WHERE e.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro ao buscar: " . $e->getMessage());
            return null;
        }
    }

    public function cadastrar($equipamento_id, $usuario_id, $data_emprestimo, $data_devolucao_prevista) {
        try {
            error_log("Iniciando cadastro de empréstimo...");
            error_log("Dados recebidos: equipamento_id=$equipamento_id, usuario_id=$usuario_id, data_emprestimo=$data_emprestimo, data_devolucao_prevista=$data_devolucao_prevista");

            // Verifica se o equipamento está disponível
            $sql = "SELECT status FROM equipamentos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $equipamento_id);
            $stmt->execute();
            $equipamento = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("Status do equipamento encontrado: " . print_r($equipamento, true));

            if (!$equipamento) {
                error_log("Equipamento não encontrado: " . $equipamento_id);
                return false;
            }

            if ($equipamento['status'] !== 'disponivel') {
                error_log("Equipamento não está disponível: " . $equipamento_id . ", status: " . $equipamento['status']);
                return false;
            }

            // Inicia a transação
            $this->db->beginTransaction();
            error_log("Transação iniciada");

            try {
                // Insere o empréstimo
                $sql = "INSERT INTO emprestimos (equipamento_id, usuario_id, data_emprestimo, data_prevista_devolucao, status) 
                        VALUES (:equipamento_id, :usuario_id, :data_emprestimo, :data_prevista_devolucao, 'ativo')";
                
                $stmt = $this->db->prepare($sql);
                
                $stmt->bindValue(':equipamento_id', $equipamento_id);
                $stmt->bindValue(':usuario_id', $usuario_id);
                $stmt->bindValue(':data_emprestimo', $data_emprestimo);
                $stmt->bindValue(':data_prevista_devolucao', $data_devolucao_prevista);
                
                error_log("Tentando inserir empréstimo...");
                if (!$stmt->execute()) {
                    $erro = $stmt->errorInfo();
                    error_log("Erro ao inserir empréstimo: " . print_r($erro, true));
                    throw new Exception("Erro ao inserir empréstimo: " . $erro[2]);
                }
                error_log("Empréstimo inserido com sucesso");

                // Atualiza o status do equipamento para 'emprestado'
                $sql = "UPDATE equipamentos SET status = 'emprestado' WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $equipamento_id);
                
                error_log("Tentando atualizar status do equipamento...");
                if (!$stmt->execute()) {
                    $erro = $stmt->errorInfo();
                    error_log("Erro ao atualizar status do equipamento: " . print_r($erro, true));
                    throw new Exception("Erro ao atualizar status do equipamento: " . $erro[2]);
                }
                error_log("Status do equipamento atualizado com sucesso");
                
                // Confirma a transação
                $this->db->commit();
                error_log("Transação confirmada com sucesso");
                return true;

            } catch (Exception $e) {
                $this->db->rollBack();
                error_log("Erro na transação: " . $e->getMessage());
                return false;
            }

        } catch (PDOException $e) {
            error_log("Erro no banco de dados: " . $e->getMessage());
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }
} 