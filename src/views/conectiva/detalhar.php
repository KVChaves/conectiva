<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    redirecionar('listar.php');
}

$controller = new ConectivaPontoController($pdo);
$ponto = $controller->getPorId($id);
$titulo = 'Detalhes do Ponto';

if (!$ponto) {
    addMensagemErro('Ponto de internet não encontrado!');
    redirecionar('listar.php');
}

$view = __DIR__ . '/detalhar_view.php';
include __DIR__ . '/../layout.php';
?>