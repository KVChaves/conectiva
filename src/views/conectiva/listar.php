<!--listar.php-->
<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$controller = new ConectivaPontoController($pdo);
$titulo = 'Listar Pontos de Internet';

$filtros = [
    'busca' => $_GET['busca'] ?? '',
    'cidade' => $_GET['cidade'] ?? '',
    'territorio' => $_GET['territorio'] ?? ''
];

// Buscar todos os dados com filtros (sem paginação)
$pontos = $controller->getAll($filtros);

$view = __DIR__ . '/listar_view.php';
include __DIR__ . '/../layout.php';
?>