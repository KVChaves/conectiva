<!--Conectiva.php-->

<?php

// Modelo não precisa de requires, mas se precisar:
// require_once __DIR__ . '/../../config/database.php';

class Conectiva {
    private $pdo;
    private $table = 'conectiva';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Buscar todos os pontos de internet
     */
    public function getAll($orderBy = 'cidade') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar ponto por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar pontos por cidade
     */
    public function getByCidade($cidade) {
        $sql = "SELECT * FROM {$this->table} WHERE cidade = :cidade ORDER BY localidade";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cidade', $cidade, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar pontos por território
     */
    public function ByTerritorio($territorio) {
        $sql = "SELECT * FROM {$this->table} WHERE territorio = :territorio ORDER BY cidade";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':territorio', $territorio, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar por velocidade
     */
    public function byVelocidade($velocidade) {
        $sql = "SELECT * FROM {$this->table} WHERE velocidade = :velocidade ORDER BY cidade";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':velocidade', $velocidade, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar por IP
     */
    public function getByIp($ip) {
        $sql = "SELECT * FROM {$this->table} WHERE ip = :ip";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Criar novo ponto de internet
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (localidade, territorio, cidade, endereco, latitude, longitude, ip, circuito, velocidade, tipo, marcador, data_instalacao, observacao) 
                VALUES 
                (:localidade, :territorio, :cidade, :endereco, :latitude, :longitude, :ip, :circuito, :velocidade, :tipo, :marcador, :data_instalacao, :observacao)";

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':localidade', $data['localidade'], PDO::PARAM_STR);
        $stmt->bindParam(':territorio', $data['territorio'], PDO::PARAM_STR);
        $stmt->bindParam(':cidade', $data['cidade'], PDO::PARAM_STR);
        $stmt->bindParam(':endereco', $data['endereco'], PDO::PARAM_STR);
        $stmt->bindParam(':latitude', $data['latitude'], PDO::PARAM_STR);
        $stmt->bindParam(':longitude', $data['longitude'], PDO::PARAM_STR);
        $stmt->bindParam(':ip', $data['ip'], PDO::PARAM_STR);
        $stmt->bindParam(':circuito', $data['circuito'], PDO::PARAM_STR);
        $stmt->bindParam(':velocidade', $data['velocidade'], PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $data['tipo'], PDO::PARAM_STR);
        $stmt->bindParam(':marcador', $data['marcador'], PDO::PARAM_STR);
        $stmt->bindParam(':data_instalacao', $data['data_instalacao'], PDO::PARAM_STR);
        $stmt->bindParam(':observacao', $data['observacao'], PDO::PARAM_STR);

        return $stmt->execute() ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Atualizar ponto de internet
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET localidade = :localidade, territorio = :territorio, cidade = :cidade, 
                    endereco = :endereco, latitude = :latitude, longitude = :longitude, 
                    ip = :ip, circuito = :circuito, velocidade = :velocidade, 
                    tipo = :tipo, marcador = :marcador, data_instalacao = :data_instalacao, 
                    observacao = :observacao 
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':localidade', $data['localidade'], PDO::PARAM_STR);
        $stmt->bindParam(':territorio', $data['territorio'], PDO::PARAM_STR);
        $stmt->bindParam(':cidade', $data['cidade'], PDO::PARAM_STR);
        $stmt->bindParam(':endereco', $data['endereco'], PDO::PARAM_STR);
        $stmt->bindParam(':latitude', $data['latitude'], PDO::PARAM_STR);
        $stmt->bindParam(':longitude', $data['longitude'], PDO::PARAM_STR);
        $stmt->bindParam(':ip', $data['ip'], PDO::PARAM_STR);
        $stmt->bindParam(':circuito', $data['circuito'], PDO::PARAM_STR);
        $stmt->bindParam(':velocidade', $data['velocidade'], PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $data['tipo'], PDO::PARAM_STR);
        $stmt->bindParam(':marcador', $data['marcador'], PDO::PARAM_STR);
        $stmt->bindParam(':data_instalacao', $data['data_instalacao'], PDO::PARAM_STR);
        $stmt->bindParam(':observacao', $data['observacao'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Deletar ponto de internet
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Contar total de pontos
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Buscar com paginação
     */
    public function paginate($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM {$this->table} ORDER BY cidade LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Validar IP duplicado
     */
    public function ipExists($ip, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE ip = :ip";
        
        if ($excludeId) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
        
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Buscar por localidade
     */
    public function byLocalidade($localidade) {
        $sql = "SELECT * FROM {$this->table} WHERE localidade LIKE :localidade";
        $stmt = $this->pdo->prepare($sql);
        $localidade = "%{$localidade}%";
        $stmt->bindParam(':localidade', $localidade, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca avançada
     */
    public function search($termo) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE localidade LIKE :termo 
                OR cidade LIKE :termo 
                OR endereco LIKE :termo 
                OR ip LIKE :termo
                OR territorio LIKE :termo
                ORDER BY cidade";

        $stmt = $this->pdo->prepare($sql);
        $termo = "%{$termo}%";
        $stmt->bindParam(':termo', $termo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>