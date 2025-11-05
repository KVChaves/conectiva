<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$controller = new ConectivaPontoController($pdo);
$titulo = 'Listar Pontos de Internet';

// Obter filtros
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$filtros = [
    'busca' => $_GET['busca'] ?? '',
    'cidade' => $_GET['cidade'] ?? '',
    'territorio' => $_GET['territorio'] ?? ''
];

// Obter dados com paginaчуo
$resultado = $controller->paginar($page, ITEMS_POR_PAGINA);
$pontos = $resultado['dados'];

// Se hс filtro de busca, aplicar
if (!empty($filtros['busca']) || !empty($filtros['cidade']) || !empty($filtros['territorio'])) {
    $pontos = $controller->getAll($filtros);
}

$view = __DIR__ . '/listar_view.php';
include __DIR__ . '/../layout.php';
?>