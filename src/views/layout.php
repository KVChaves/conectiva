<?php
require_once __DIR__ . '/../../src/Utilities/functions.php';

$titulo = $titulo ?? 'CONECTIVA';
$mensagens = obterMensagens();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?> - CONECTIVA</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo getUrlBase(); ?>/public/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo getUrlBase(); ?>/index.php">
                <i class="fas fa-globe"></i> CONECTIVA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                </ul>
            </div>
        </div>
    </nav>

    <!-- Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- CONTAINER PRINCIPAL -->
    <div class="container-fluid">
        <div class="row main-row">
            <!-- SIDEBAR - SEMPRE VISÍVEL -->
            <nav class="sidebar-custom" id="sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" 
                           href="<?php echo getUrlBase(); ?>/index.php">
                            <i class="fas fa-map"></i>
                            <span>Mapa de Pontos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'listar.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo getUrlBase(); ?>/src/Views/conectiva/listar.php">
                            <i class="fas fa-list"></i>
                            <span>Listar Pontos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'criar.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo getUrlBase(); ?>/src/Views/conectiva/criar.php">
                            <i class="fas fa-plus"></i>
                            <span>Novo Ponto</span>
                        </a>
                    </li>
                    <li class="nav-item">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'dashboard.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo getUrlBase(); ?>/src/Views/relatorios/dashboard.php">
                            <i class="fas fa-chart-bar"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'helpdesk.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo getUrlBase(); ?>/src/Views/relatorios/helpdesk.php">
                            <i class="fas fas fa-headset"></i>
                            <span>Helpdesk</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- MAIN CONTENT -->
            <main class="main-content">
                <div class="main-content-inner">
                    <!-- MENSAGENS DE ALERTA -->
                    <?php if (!empty($mensagens)): ?>
                        <?php foreach ($mensagens as $mensagem): ?>
                            <?php echo exibirAlerta($mensagem['tipo'], $mensagem['mensagem']); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- CONTEÚDO -->
                    <?php 
                    if (isset($view) && file_exists($view)) {
                        include $view;
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo getUrlBase(); ?>/public/js/main.js"></script>
    
    <!-- Script para Toggle Sidebar (Mobile) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                // Toggle sidebar
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                });
                
                // Fechar sidebar ao clicar fora (apenas mobile)
                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.contains(event.target) && 
                            !sidebarToggle.contains(event.target) && 
                            sidebar.classList.contains('show')) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
                
                // Fechar sidebar ao clicar em um link (mobile)
                const sidebarLinks = sidebar.querySelectorAll('.nav-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('show');
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>