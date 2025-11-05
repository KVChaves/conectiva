<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$edicao = false;
$ponto = [];
$erros = [];

// Se for POST, processar o formulário
if (ehPost()) {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || !verificarTokenCsrf($_POST['csrf_token'])) {
        addMensagemErro('Token de segurança inválido!');
        redirecionar('criar.php');
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

    $controller = new ConectivaPontoController($pdo);
    $resultado = $controller->criar($dados);

    if ($resultado['sucesso']) {
        addMensagemSucesso($resultado['mensagem']);
        redirecionar('listar.php');
    } else {
        $erros = $resultado['erros'] ?? [];
        if (isset($resultado['mensagem'])) {
            addMensagemErro($resultado['mensagem']);
        }
    }
}

$titulo = 'Criar Novo Ponto de Internet';
$view = __DIR__ . '/formulario.php';
include __DIR__ . '/../layout.php';
?>