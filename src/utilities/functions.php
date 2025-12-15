<?php

/**
 * Sanitizar entrada de dados
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES);
}

/**
 * Validar email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar IP
 */
function validarIp($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

/**
 * Formatar data para exibição
 */
function formatarData($data, $formato = 'd/m/Y') {
    if (empty($data)) {
        return '-';
    }
    
    try {
        $date = DateTime::createFromFormat('Y-m-d', $data);
        return $date ? $date->format($formato) : '-';
    } catch (Exception $e) {
        return '-';
    }
}

/**
 * Formatar data/hora para exibição
 */
function formatarDataHora($dataHora, $formato = 'd/m/Y H:i:s') {
    if (empty($dataHora)) {
        return '-';
    }
    
    try {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dataHora);
        return $date ? $date->format($formato) : '-';
    } catch (Exception $e) {
        return '-';
    }
}

/**
 * Redirecionar para página
 */
function redirecionar($url) {
    header("Location: {$url}");
    exit;
}

/**
 * Obter URL base da aplicação
 */
function getUrlBase() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    
    // Remove /src/Views/conectiva ou /src/Views/relatorios se estiver nessas pastas
    $path = preg_replace('#/(src/Views|public).*#', '', $path);
    
    return "{$protocol}://{$host}{$path}";
}

/**
 * Registrar mensagem de sucesso na sessão
 */
function addMensagemSucesso($mensagem) {
    if (!isset($_SESSION['mensagens'])) {
        $_SESSION['mensagens'] = [];
    }
    $_SESSION['mensagens'][] = [
        'tipo' => 'sucesso',
        'mensagem' => $mensagem
    ];
}

/**
 * Registrar mensagem de erro na sessão
 */
function addMensagemErro($mensagem) {
    if (!isset($_SESSION['mensagens'])) {
        $_SESSION['mensagens'] = [];
    }
    $_SESSION['mensagens'][] = [
        'tipo' => 'erro',
        'mensagem' => $mensagem
    ];
}

/**
 * Registrar mensagem de aviso na sessão
 */
function addMensagemAviso($mensagem) {
    if (!isset($_SESSION['mensagens'])) {
        $_SESSION['mensagens'] = [];
    }
    $_SESSION['mensagens'][] = [
        'tipo' => 'aviso',
        'mensagem' => $mensagem
    ];
}

/**
 * Obter e limpar mensagens da sessão
 */
function obterMensagens() {
    $mensagens = $_SESSION['mensagens'] ?? [];
    unset($_SESSION['mensagens']);
    return $mensagens;
}

/**
 * Exibir alerta Bootstrap
 */
function exibirAlerta($tipo, $mensagem) {
    $classes = [
        'sucesso' => 'alert alert-success',
        'erro' => 'alert alert-danger',
        'aviso' => 'alert alert-warning',
        'info' => 'alert alert-info'
    ];
    
    $classe = $classes[$tipo] ?? 'alert alert-info';
    
    return "<div class='{$classe}' role='alert'>{$mensagem}</div>";
}

/**
 * Validar coordenadas (latitude e longitude)
 */
function validarCoordenadas($latitude, $longitude) {
    $lat = floatval($latitude);
    $lng = floatval($longitude);
    
    return $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
}

/**
 * Calcular distância entre dois pontos (Haversine formula)
 */
function calcularDistancia($lat1, $lon1, $lat2, $lon2) {
    $terra = 6371; // Raio da Terra em km
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    return $terra * $c;
}

/**
 * Formatar tamanho de arquivo
 */
function formatarTamanhoArquivo($bytes) {
    $unidades = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($unidades) - 1);
    $bytes /= (1 << (10 * $pow));
    
    return round($bytes, 2) . ' ' . $unidades[$pow];
}

/**
 * Escapar para JSON
 */
function escaparJson($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Verificar se é requisição AJAX
 */
function ehAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Obter método HTTP
 */
function getMetodoHttp() {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

/**
 * Verificar se é método POST
 */
function ehPost() {
    return getMetodoHttp() === 'POST';
}

/**
 * Verificar se é método GET
 */
function ehGet() {
    return getMetodoHttp() === 'GET';
}

/**
 * Proteger contra CSRF (verificar token)
 */
function verificarTokenCsrf($token) {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

/**
 * Gerar token CSRF
 */
function gerarTokenCsrf() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

?>