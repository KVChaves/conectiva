<!--helpdesk.php-->
<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/HelpdeskController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$controller = new HelpdeskController($pdo);
$titulo = 'Helpdesk - Listar Chamados';

// Obter filtros
$filtros = [
    'status' => $_GET['status'] ?? '',
    'tipo_problema' => $_GET['tipo_problema'] ?? '',
    'cidade' => $_GET['cidade'] ?? '',
    'localidade' => $_GET['localidade'] ?? ''
];

// Obter dados
$chamados = $controller->getAll($filtros);
$stats = $controller->getEstatisticas();

$view = __DIR__ . '/helpdesk_view.php';
include __DIR__ . '/../layout.php';
?>