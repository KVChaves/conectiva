<?php

class Helpdesk {
    private $pdo;
    private $table = 'conectiva_helpdesk';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Buscar todos os chamados
     */
    public function getAll($orderBy = 'data_abertura DESC') {
        $sql = "SELECT h.*, c.localidade, c.cidade, c.ip 
                FROM {$this->table} h
                INNER JOIN conectiva c ON h.ponto_id = c.id
                ORDER BY {$orderBy}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar chamado por ID
     */
    public function getById($id) {
        $sql = "SELECT h.*, c.localidade, c.cidade, c.ip, c.endereco, c.velocidade 
                FROM {$this->table} h
                INNER JOIN conectiva c ON h.ponto_id = c.id
                WHERE h.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar chamados por status
     */
    public function getByStatus($status) {
        $sql = "SELECT h.*, c.localidade, c.cidade, c.ip 
                FROM {$this->table} h
                INNER JOIN conectiva c ON h.ponto_id = c.id
                WHERE h.status = :status
                ORDER BY h.data_abertura DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar chamados por ponto
     */
    public function getByPonto($ponto_id) {
        $sql = "SELECT h.*, c.localidade, c.cidade, c.ip 
                FROM {$this->table} h
                INNER JOIN conectiva c ON h.ponto_id = c.id
                WHERE h.ponto_id = :ponto_id
                ORDER BY h.data_abertura DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':ponto_id', $ponto_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Criar novo chamado
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (ponto_id, tipo_problema, status, data_abertura) 
                VALUES 
                (:ponto_id, :tipo_problema, :status, NOW())";

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':ponto_id', $data['ponto_id'], PDO::PARAM_INT);
        $stmt->bindParam(':tipo_problema', $data['tipo_problema'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'] ?? 'Aberto', PDO::PARAM_STR);

        return $stmt->execute() ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Atualizar chamado
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET ponto_id = :ponto_id, 
                    tipo_problema = :tipo_problema, 
                    status = :status, 
                    data_fechamento = :data_fechamento,
                    data_atualizacao = NOW()
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ponto_id', $data['ponto_id'], PDO::PARAM_INT);
        $stmt->bindParam(':tipo_problema', $data['tipo_problema'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(':data_fechamento', $data['data_fechamento'] ?? null);

        return $stmt->execute();
    }

    /**
     * Deletar chamado
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Contar total de chamados
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Contar chamados abertos
     */
    public function countAbertos() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'Aberto'";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Buscar com filtros
     */
    public function search($filtros = []) {
        $sql = "SELECT h.*, c.localidade, c.cidade, c.ip 
                FROM {$this->table} h
                INNER JOIN conectiva c ON h.ponto_id = c.id
                WHERE 1=1";

        if (!empty($filtros['status'])) {
            $sql .= " AND h.status = :status";
        }

        if (!empty($filtros['tipo_problema'])) {
            $sql .= " AND h.tipo_problema = :tipo_problema";
        }

        if (!empty($filtros['cidade'])) {
            $sql .= " AND c.cidade = :cidade";
        }

        if (!empty($filtros['localidade'])) {
            $sql .= " AND c.localidade LIKE :localidade";
        }

        $sql .= " ORDER BY h.data_abertura DESC";

        $stmt = $this->pdo->prepare($sql);

        if (!empty($filtros['status'])) {
            $stmt->bindParam(':status', $filtros['status'], PDO::PARAM_STR);
        }

        if (!empty($filtros['tipo_problema'])) {
            $stmt->bindParam(':tipo_problema', $filtros['tipo_problema'], PDO::PARAM_STR);
        }

        if (!empty($filtros['cidade'])) {
            $stmt->bindParam(':cidade', $filtros['cidade'], PDO::PARAM_STR);
        }

        if (!empty($filtros['localidade'])) {
            $localidade = "%{$filtros['localidade']}%";
            $stmt->bindParam(':localidade', $localidade, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obter estatísticas
     */
    public function getEstatisticas() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Aberto' THEN 1 ELSE 0 END) as abertos,
                SUM(CASE WHEN status = 'Fechado' THEN 1 ELSE 0 END) as fechados,
                COUNT(DISTINCT tipo_problema) as tipos_problema
                FROM {$this->table}";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>