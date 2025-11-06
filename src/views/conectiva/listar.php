<!--listar.php-->
<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$controller = new ConectivaPontoController($pdo);
$titulo = 'Listar Pontos de Internet';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$filtros = [
    'busca' => $_GET['busca'] ?? '',
    'cidade' => $_GET['cidade'] ?? '',
    'territorio' => $_GET['territorio'] ?? ''
];

// Usar o novo método que combina paginação + filtros
$resultado = $controller->paginarComFiltros($page, ITEMS_POR_PAGINA, $filtros);
$pontos = $resultado['dados'];

$view = __DIR__ . '/listar_view.php';
include __DIR__ . '/../layout.php';
?>