<!--ConectivaPontoController.php-->
<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Conectiva.php';

class ConectivaPontoController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Conectiva($pdo);
    }

    /**
     * Validar dados do formulário
     */
    public function validarDados($data) {
        $erros = [];

        // Validar localidade
        if (empty($data['localidade'])) {
            $erros['localidade'] = 'Localidade é obrigatória';
        }

        // Validar território
        if (empty($data['territorio'])) {
            $erros['territorio'] = 'Território é obrigatório';
        }

        // Validar cidade
        if (empty($data['cidade'])) {
            $erros['cidade'] = 'Cidade é obrigatória';
        }

        // Validar endereço
        if (empty($data['endereco'])) {
            $erros['endereco'] = 'Endereço é obrigatório';
        }

        // Validar latitude
        if (empty($data['latitude']) || !is_numeric($data['latitude'])) {
            $erros['latitude'] = 'Latitude inválida';
        }

        // Validar longitude
        if (empty($data['longitude']) || !is_numeric($data['longitude'])) {
            $erros['longitude'] = 'Longitude inválida';
        }

        // Validar IP
        if (empty($data['ip'])) {
            $erros['ip'] = 'IP é obrigatório';
        } elseif (!filter_var($data['ip'], FILTER_VALIDATE_IP)) {
            $erros['ip'] = 'IP inválido';
        } elseif ($this->model->ipExists($data['ip'], $data['id'] ?? null)) {
            $erros['ip'] = 'IP já existe no sistema';
        }

        // Validar velocidade
        if (empty($data['velocidade'])) {
            $erros['velocidade'] = 'Velocidade é obrigatória';
        }

        // Validar data de instalação
        if (empty($data['data_instalacao'])) {
            $erros['data_instalacao'] = 'Data de instalação é obrigatória';
        } else {
            $data_instalacao = DateTime::createFromFormat('Y-m-d', $data['data_instalacao']);
            if (!$data_instalacao) {
                $erros['data_instalacao'] = 'Data de instalação inválida';
            }
        }

        return $erros;
    }

    /**
     * Criar novo ponto
     */
    public function criar($data) {
        $erros = $this->validarDados($data);

        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros,
                'mensagem' => 'Verifique os erros no formulário'
            ];
        }

        try {
            $id = $this->model->create($data);
            return [
                'sucesso' => true,
                'id' => $id,
                'mensagem' => 'Ponto de internet criado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao criar ponto: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Atualizar ponto
     */
    public function atualizar($id, $data) {
        $data['id'] = $id;
        $erros = $this->validarDados($data);

        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros,
                'mensagem' => 'Verifique os erros no formulário'
            ];
        }

        // Verificar se ponto existe
        $ponto = $this->model->getById($id);
        if (!$ponto) {
            return [
                'sucesso' => false,
                'mensagem' => 'Ponto de internet não encontrado'
            ];
        }

        try {
            $this->model->update($id, $data);
            return [
                'sucesso' => true,
                'mensagem' => 'Ponto de internet atualizado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar ponto: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Deletar ponto
     */
    public function deletar($id) {
        $ponto = $this->model->getById($id);
        if (!$ponto) {
            return [
                'sucesso' => false,
                'mensagem' => 'Ponto de internet não encontrado'
            ];
        }

        try {
            $this->model->delete($id);
            return [
                'sucesso' => true,
                'mensagem' => 'Ponto de internet deletado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao deletar ponto: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obter todos os pontos
     */
    public function getAll($filtros = []) {
        try {
            if (!empty($filtros['busca'])) {
                return $this->model->search($filtros['busca']);
            }

            if (!empty($filtros['cidade'])) {
                return $this->model->getByCidade($filtros['cidade']);
            }

            if (!empty($filtros['territorio'])) {
                return $this->model->byTerritorio($filtros['territorio']);
            }

            return $this->model->getAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obter ponto por ID
     */
    public function getPorId($id) {
        return $this->model->getById($id);
    }

    /**
     * Contar total
     */
    public function contar() {
        return $this->model->count();
    }

    /**
     * Obter com paginação
     */
    public function paginar($page = 1, $limit = 10) {
        $total = $this->model->count();
        $dados = $this->model->paginate($page, $limit);
        
        return [
            'dados' => $dados,
            'total' => $total,
            'pagina' => $page,
            'limite' => $limit,
            'total_paginas' => ceil($total / $limit)
        ];
    }

    /**
     * Obter com paginação E filtros
     */
    public function paginarComFiltros($page = 1, $limit = 10, $filtros = []) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM conectiva WHERE 1=1";
        $params = [];

        // Busca por texto
        if (!empty($filtros['busca'])) {
            $sql .= " AND (localidade LIKE ? OR endereco LIKE ? OR ip LIKE ?)";
            $params[] = '%' . $filtros['busca'] . '%';
            $params[] = '%' . $filtros['busca'] . '%';
            $params[] = '%' . $filtros['busca'] . '%';
        }

        // Filtro por cidade
        if (!empty($filtros['cidade'])) {
            $sql .= " AND cidade = ?";
            $params[] = $filtros['cidade'];
        }

        // Filtro por território
        if (!empty($filtros['territorio'])) {
            $sql .= " AND territorio = ?";
            $params[] = $filtros['territorio'];
        }

        // Contar total com filtros
        $stmtCount = $this->pdo->prepare(str_replace('SELECT *', 'SELECT COUNT(*) as total', $sql));
        $stmtCount->execute($params);
        $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

        // Buscar dados com paginação
        $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'dados' => $dados,
            'total' => $total,
            'pagina' => $page,
            'limite' => $limit,
            'total_paginas' => ceil($total / $limit)
        ];
    }

    /**
     * Obter estatísticas
     */
    public function getEstatisticas() {
        $sql = "SELECT 
                COUNT(*) as total_pontos,
                COUNT(DISTINCT cidade) as total_cidades,
                COUNT(DISTINCT territorio) as total_territorios
                FROM conectiva";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>