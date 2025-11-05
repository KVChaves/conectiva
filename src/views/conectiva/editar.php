<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    addMensagemErro('ID inválido!');
    redirecionar('listar.php');
}

$controller = new ConectivaPontoController($pdo);
$ponto = $controller->getPorId($id);

if (!$ponto) {
    addMensagemErro('Ponto de internet não encontrado!');
    redirecionar('listar.php');
}

$edicao = true;
$erros = [];

// Se for POST, processar o formulário
if (ehPost()) {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || !verificarTokenCsrf($_POST['csrf_token'])) {
        addMensagemErro('Token de segurança inválido!');
        redirecionar('editar.php?id=' . $id);
    }

    // Preparar dados
    $dados = [
        'localidade' => sanitize($_POST['localidade'] ?? ''),
        'territorio' => sanitize($_POST['territorio'] ?? ''),
        'cidade' => sanitize($_POST['cidade'] ?? ''),
        'endereco' => sanitize($_POST['endereco'] ?? ''),
        'latitude' => $_POST['latitude'] ?? '',
        'longitude' => $_POST['longitude'] ?? '',
        'ip' => sanitize($_POST['ip'] ?? ''),
        'circuito' => sanitize($_POST['circuito'] ?? ''),
        'velocidade' => sanitize($_POST['velocidade'] ?? ''),
        'tipo' => sanitize($_POST['tipo'] ?? ''),
        'marcador' => sanitize($_POST['marcador'] ?? ''),
        'data_instalacao' => $_POST['data_instalacao'] ?? '',
        'observacao' => sanitize($_POST['observacao'] ?? '')
    ];

    $resultado = $controller->atualizar($id, $dados);

    if ($resultado['sucesso']) {
        addMensagemSucesso($resultado['mensagem']);
        redirecionar('detalhar.php?id=' . $id);
    } else {
        $erros = $resultado['erros'] ?? [];
        if (isset($resultado['mensagem'])) {
            addMensagemErro($resultado['mensagem']);
        }
        // Manter dados do formulário para reedição
        $ponto = array_merge($ponto, $dados);
    }
}

$titulo = 'Editar Ponto de Internet';
$view = __DIR__ . '/formulario.php';
include __DIR__ . '/../layout.php';
?>