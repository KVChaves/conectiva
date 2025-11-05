<?php
session_start();

// Incluir configurações
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Utilities/functions.php';

// Obter todos os pontos de internet do banco de dados
$sql = "SELECT * FROM conectiva ORDER BY cidade";

$pontos = [];
try {
    $result = $pdo->query($sql);
    if ($result) {
        $pontos = $result->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $pontos = [];
    error_log("Erro ao buscar dados: " . $e->getMessage());
}

// Debug - Verificar estrutura dos dados
error_log("Total de pontos: " . count($pontos));
if (!empty($pontos)) {
    error_log("Primeiro ponto: " . print_r($pontos[0], true));
    error_log("JSON encode error: " . json_last_error_msg());
}

$titulo = 'Mapa de Pontos de Internet';
$view = __DIR__ . '/src/Views/mapa/mapa_view.php';
include __DIR__ . '/src/Views/layout.php';
?>