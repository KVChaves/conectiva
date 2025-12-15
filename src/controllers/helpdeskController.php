<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Helpdesk.php';

class HelpdeskController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Helpdesk($pdo);
    }

    /**
     * Validar dados do formulário
     */
    public function validarDados($data) {
        $erros = [];

        // Validar ponto_id
        if (empty($data['ponto_id'])) {
            $erros['ponto_id'] = 'Ponto de internet é obrigatório';
        } else {
            // Verificar se ponto existe
            $sql = "SELECT id FROM conectiva WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['ponto_id'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                $erros['ponto_id'] = 'Ponto de internet não encontrado';
            }
        }

        // Validar tipo_problema
        if (empty($data['tipo_problema'])) {
            $erros['tipo_problema'] = 'Tipo de problema é obrigatório';
        } else {
            $tipos_validos = ['Lentidão', 'Rompimento de Fibra', 'Queda de Rede', 'Intermitência', 'Sem Conexão', 'Problema de Equipamento', 'Outros'];
            if (!in_array($data['tipo_problema'], $tipos_validos)) {
                $erros['tipo_problema'] = 'Tipo de problema inválido';
            }
        }

        // Validar status
        if (!empty($data['status'])) {
            $status_validos = ['Aberto', 'Fechado'];
            if (!in_array($data['status'], $status_validos)) {
                $erros['status'] = 'Status inválido';
            }
        }

        return $erros;
    }

    /**
     * Criar novo chamado
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
                'mensagem' => 'Chamado criado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao criar chamado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Atualizar chamado
     */
    public function atualizar($id, $data) {
        $erros = $this->validarDados($data);

        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros,
                'mensagem' => 'Verifique os erros no formulário'
            ];
        }

        // Verificar se chamado existe
        $chamado = $this->model->getById($id);
        if (!$chamado) {
            return [
                'sucesso' => false,
                'mensagem' => 'Chamado não encontrado'
            ];
        }

        try {
            $this->model->update($id, $data);
            return [
                'sucesso' => true,
                'mensagem' => 'Chamado atualizado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar chamado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Deletar chamado
     */
    public function deletar($id) {
        $chamado = $this->model->getById($id);
        if (!$chamado) {
            return [
                'sucesso' => false,
                'mensagem' => 'Chamado não encontrado'
            ];
        }

        try {
            $this->model->delete($id);
            return [
                'sucesso' => true,
                'mensagem' => 'Chamado deletado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao deletar chamado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obter todos os chamados
     */
    public function getAll($filtros = []) {
        try {
            if (!empty($filtros)) {
                return $this->model->search($filtros);
            }
            return $this->model->getAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obter chamado por ID
     */
    public function getPorId($id) {
        return $this->model->getById($id);
    }

    /**
     * Obter estatísticas
     */
    public function getEstatisticas() {
        return $this->model->getEstatisticas();
    }

    /**
     * Obter chamados abertos
     */
    public function getChamadosAbertos() {
        return $this->model->getByStatus('Aberto');
    }

    /**
     * Obter chamados fechados
     */
    public function getChamadosFechados() {
        return $this->model->getByStatus('Fechado');
    }

    /**
     * Fechar chamado
     */
    public function fecharChamado($id) {
        $chamado = $this->model->getById($id);
        if (!$chamado) {
            return [
                'sucesso' => false,
                'mensagem' => 'Chamado não encontrado'
            ];
        }

        try {
            $this->model->update($id, [
                'ponto_id' => $chamado['ponto_id'],
                'tipo_problema' => $chamado['tipo_problema'],
                'status' => 'Fechado',
                'data_fechamento' => date('Y-m-d H:i:s')
            ]);
            return [
                'sucesso' => true,
                'mensagem' => 'Chamado fechado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao fechar chamado: ' . $e->getMessage()
            ];
        }
    }
}

?>