<div class="page-title">
    <i class="fas fa-list"></i> Pontos de Internet
</div>

<div class="card mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-search"></i> Filtros de Busca
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="busca" class="form-label">Buscar</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="busca" 
                    name="busca" 
                    placeholder="Localidade, cidade, IP..."
                    value="<?php echo htmlspecialchars($filtros['busca']); ?>"
                >
            </div>

            <div class="col-md-4">
                <label for="territorio" class="form-label">Território</label>
                <select class="form-select" id="territorio" name="territorio">
                    <option value="">-- Todos os Territórios --</option>
                    <?php foreach (array_keys($GLOBALS['TERRITORIOS']) as $terr): ?>
                        <option value="<?php echo htmlspecialchars($terr); ?>" 
                                <?php echo $filtros['territorio'] === $terr ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($terr); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="cidade" class="form-label">Cidade</label>
                <select class="form-select" id="cidade" name="cidade">
                    <option value="">-- Todas as Cidades --</option>
                    <?php 
                    $cidades = [];
                    foreach ($GLOBALS['TERRITORIOS'] as $terr => $cids) {
                        $cidades = array_merge($cidades, $cids);
                    }
                    $cidades = array_unique($cidades);
                    sort($cidades);
                    foreach ($cidades as $cid): 
                    ?>
                        <option value="<?php echo htmlspecialchars($cid); ?>" 
                                <?php echo $filtros['cidade'] === $cid ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cid); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-primary-custom">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="listar.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Limpar
                </a>
                <a href="criar.php" class="btn btn-success float-end">
                    <i class="fas fa-plus"></i> Novo Ponto
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-globe"></i> Total de Pontos: <?php echo count($pontos); ?>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Localidade</th>
                    <th>Cidade</th>
                    <th>IP</th>
                    <th>Velocidade</th>
                    <th>Tipo</th>
                    <th>Data Instalação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pontos)): ?>
                    <?php foreach ($pontos as $index => $ponto): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($ponto['localidade']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($ponto['cidade']); ?></td>
                            <td>
                                <code><?php echo htmlspecialchars($ponto['ip']); ?></code>
                            </td>
                            <td>
                                <span class="badge bg-info badge-custom">
                                    <?php echo htmlspecialchars($ponto['velocidade']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($ponto['tipo'] ?? '-'); ?></td>
                            <td>
                                <small><?php echo formatarData($ponto['data_instalacao']); ?></small>
                            </td>
                            <td>
                                <a href="detalhar.php?id=<?php echo $ponto['id']; ?>" 
                                   class="btn btn-sm btn-info" title="Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="editar.php?id=<?php echo $ponto['id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="confirmarDelecao(<?php echo $ponto['id']; ?>)"
                                        title="Deletar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum ponto encontrado</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Paginação -->
<?php if (isset($resultado) && $resultado['total_paginas'] > 1): ?>
    <nav aria-label="Paginação" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1<?php echo !empty($filtros['busca']) ? '&busca=' . urlencode($filtros['busca']) : ''; ?>">
                        Primeira
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($filtros['busca']) ? '&busca=' . urlencode($filtros['busca']) : ''; ?>">
                        Anterior
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $resultado['total_paginas']; $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($filtros['busca']) ? '&busca=' . urlencode($filtros['busca']) : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $resultado['total_paginas']): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($filtros['busca']) ? '&busca=' . urlencode($filtros['busca']) : ''; ?>">
                        Próxima
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $resultado['total_paginas']; ?><?php echo !empty($filtros['busca']) ? '&busca=' . urlencode($filtros['busca']) : ''; ?>">
                        Última
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>

<script>
function confirmarDelecao(id) {
    if (confirm('Deseja realmente deletar este ponto?')) {
        window.location.href = 'deletar.php?id=' + id + '&confirm=true';
    }
}
</script>