<div class="page-title">
    <i class="fas fa-chart-bar"></i> Dashboard
</div>

<!-- Cards de Estatísticas -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Total de Pontos</h6>
                <h2 class="card-text" style="color: #667eea;">
                    <i class="fas fa-globe"></i> <?php echo $stats['total_pontos']; ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Cidades Atendidas</h6>
                <h2 class="card-text" style="color: #10b981;">
                    <i class="fas fa-city"></i> <?php echo $stats['total_cidades']; ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Territórios</h6>
                <h2 class="card-text" style="color: #f59e0b;">
                    <i class="fas fa-map"></i> <?php echo $stats['total_territorios']; ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mb-4">
    <!-- Pontos por Velocidade -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-tachometer-alt"></i> Pontos por Velocidade
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Velocidade</th>
                            <th>Quantidade</th>
                            <th>Percentual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pontosPorVelocidade as $velocidade => $quantidade): ?>
                            <?php $percentual = ($quantidade / $stats['total_pontos']) * 100; ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($velocidade); ?></strong></td>
                                <td><?php echo $quantidade; ?></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: <?php echo $percentual; ?>%" 
                                             aria-valuenow="<?php echo $percentual; ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?php echo round($percentual, 1); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pontos por Tipo -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-network-wired"></i> Pontos por Tipo
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Percentual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pontosPorTipo as $tipo => $quantidade): ?>
                            <?php $percentual = ($quantidade / $stats['total_pontos']) * 100; ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($tipo); ?></strong></td>
                                <td><?php echo $quantidade; ?></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?php echo $percentual; ?>%" 
                                             aria-valuenow="<?php echo $percentual; ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?php echo round($percentual, 1); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Filtros -->

<div class="card mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-filter"></i> Filtrar Resultados
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <label for="territorio" class="form-label">Território</label>
                <select class="form-select" id="territorio" name="territorio">
                    <option value="">-- Todos os Territórios --</option>
                    <?php foreach (array_keys($GLOBALS['territorios']) as $terr): ?>
                        <option value="<?php echo htmlspecialchars($terr); ?>" 
                                <?php echo $territorio === $terr ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($terr); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="cidade" class="form-label">Cidade</label>
                <select class="form-select" id="cidade" name="cidade">
                    <option value="">-- Todas as Cidades --</option>
                    <?php 
                    $cidades = [];
                    foreach ($GLOBALS['territorios'] as $cids) {
                        $cidades = array_merge($cidades, $cids);
                    }
                    $cidades = array_unique($cidades);
                    sort($cidades);
                    foreach ($cidades as $cid): 
                    ?>
                        <option value="<?php echo htmlspecialchars($cid); ?>" 
                                <?php echo $cidade === $cid ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cid); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-primary-custom">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela Completa -->
<div class="card mt-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-table"></i> Todos os Pontos
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Localidade</th>
                    <th>Cidade</th>
                    <th>Velocidade</th>
                    <th>Tipo</th>
                    <th>Territorio</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pontos)): ?>
                    <?php foreach ($pontos as $index => $ponto): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><strong><?php echo htmlspecialchars($ponto['localidade']); ?></strong></td>
                            <td><?php echo htmlspecialchars($ponto['cidade']); ?></td>
                            <td>
                                <span class="badge bg-info badge-custom">
                                    <?php echo htmlspecialchars($ponto['velocidade']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($ponto['tipo'] ?? '-'); ?></td>
                            <td><small><?php echo htmlspecialchars(substr($ponto['territorio'], 0, 15)); ?></small></td>
                            
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum ponto encontrado com os filtros selecionados</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Botões de Ação -->
<div class="row mt-4">
    <div class="col-12">
        <a href="../../index.php" class="btn btn-primary btn-primary-custom">
            <i class="fas fa-map"></i> Ver Mapa
        </a>
        <a href="../conectiva/listar.php" class="btn btn-info">
            <i class="fas fa-list"></i> Listar Pontos
        </a>
    </div>
</div>