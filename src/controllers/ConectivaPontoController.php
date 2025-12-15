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