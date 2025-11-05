<?php
session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../src/Utilities/functions.php';

$id = (int)($_GET['id'] ?? 0);
$confirm = isset($_GET['confirm']) && $_GET['confirm'] === 'true';

// Validar ID
if ($id <= 0) {
    addMensagemErro('ID inválido!');
    redirecionar('listar.php');
}

$controller = new ConectivaPontoController($pdo);
$ponto = $controller->getPorId($id);

// Verificar se ponto existe
if (!$ponto) {
    addMensagemErro('Ponto de internet não encontrado!');
    redirecionar('listar.php');
}

// Se não foi confirmado, redirecionar para listagem
if (!$confirm) {
    addMensagemAviso('Ação não confirmada!');
    redirecionar('listar.php');
}

// Processar exclusão
$resultado = $controller->deletar($id);

if ($resultado['sucesso']) {
    addMensagemSucesso($resultado['mensagem']);
} else {
    addMensagemErro($resultado['mensagem']);
}

redirecionar('listar.php');
?>