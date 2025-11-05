<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../src/Controllers/ConectivaPontoController.php';
require_once __DIR__ . '/../../../src/Utilities/functions.php';

$controller = new ConectivaPontoController($pdo);
$titulo = 'Dashboard';

// Obter estatísticas
$stats = $controller->getEstatisticas();
$pontos = $controller->getAll();



// Agrupar pontos por velocidade
$pontosPorVelocidade = [];
foreach ($pontos as $ponto) {
    $vel = $ponto['velocidade'];
    if (!isset($pontosPorVelocidade[$vel])) {
        $pontosPorVelocidade[$vel] = 0;
    }
    $pontosPorVelocidade[$vel]++;
}

// Agrupar pontos por tipo
$pontosPorTipo = [];
foreach ($pontos as $ponto) {
    $tipo = $ponto['tipo'] ?? 'Não definido';
    if (!isset($pontosPorTipo[$tipo])) {
        $pontosPorTipo[$tipo] = 0;
    }
    $pontosPorTipo[$tipo]++;
}

// Agrupar pontos por cidade (top 10)
$pontosPorCidade = [];
foreach ($pontos as $ponto) {
    $cidade = $ponto['cidade'];
    if (!isset($pontosPorCidade[$cidade])) {
        $pontosPorCidade[$cidade] = 0;
    }
    $pontosPorCidade[$cidade]++;
}
arsort($pontosPorCidade);
$pontosPorCidade = array_slice($pontosPorCidade, 0, 10);

//----------------------------------PARTE 2

// Obter filtros
$territorio = isset($_GET['territorio']) ? sanitize($_GET['territorio']) : '';
$cidade = isset($_GET['cidade']) ? sanitize($_GET['cidade']) : '';

// Obter pontos com filtro se houver
$filtros = [];
if (!empty($territorio)) {
    $filtros['territorio'] = $territorio;
}
if (!empty($cidade)) {
    $filtros['cidade'] = $cidade;
}

$pontos = $controller->getAll($filtros);

// Calcular estatísticas gerais
$stats = $controller->getEstatisticas();

// Agrupar por velocidade
$pontosPorVelocidade = [];
foreach ($pontos as $ponto) {
    $vel = $ponto['velocidade'];
    if (!isset($pontosPorVelocidade[$vel])) {
        $pontosPorVelocidade[$vel] = 0;
    }
    $pontosPorVelocidade[$vel]++;
}

// Agrupar por tipo
$pontosPorTipo = [];
foreach ($pontos as $ponto) {
    $tipo = $ponto['tipo'] ?? 'Não definido';
    if (!isset($pontosPorTipo[$tipo])) {
        $pontosPorTipo[$tipo] = 0;
    }
    $pontosPorTipo[$tipo]++;
}

// Agrupar por território
$pontosPorTerritorio = [];
foreach ($pontos as $ponto) {
    $terr = $ponto['territorio'];
    if (!isset($pontosPorTerritorio[$terr])) {
        $pontosPorTerritorio[$terr] = 0;
    }
    $pontosPorTerritorio[$terr]++;
}

$view = __DIR__ . '/dashboard_view.php';
include __DIR__ . '/../layout.php';
?>