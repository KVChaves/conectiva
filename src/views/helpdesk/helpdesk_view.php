<!--helpdesk_view.php-->
<div class="page-title">
    <i class="fas fa-ticket-alt"></i> Helpdesk - Chamados
</div>

<!-- ESTATÍSTICAS -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Total de Chamados</h6>
                <h2 style="color: #176b25;"><?php echo $stats['total']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Abertos</h6>
                <h2 style="color: #ef4444;"><?php echo $stats['abertos'] ?? 0; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Fechados</h6>
                <h2 style="color: #10b981;"><?php echo $stats['fechados'] ?? 0; ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- FILTROS -->
<div class="card mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-search"></i> Filtros
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">-- Todos --</option>
                    <option value="Aberto" <?php echo $filtros['status'] === 'Aberto' ? 'selected' : ''; ?>>Aberto</option>
                    <option value="Fechado" <?php echo $filtros['status'] === 'Fechado' ? 'selected' : ''; ?>>Fechado</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="tipo_problema" class="form-label">Tipo de Problema</label>
                <select class="form-select" id="tipo_problema" name="tipo_problema">
                    <option value="">-- Todos --</option>
                    <option value="Lentidão" <?php echo $filtros['tipo_problema'] === 'Lentidão' ? 'selected' : ''; ?>>Lentidão</option>
                    <option value="Rompimento de Fibra" <?php echo $filtros['tipo_problema'] === 'Rompimento de Fibra' ? 'selected' : ''; ?>>Rompimento de Fibra</option>
                    <option value="Queda de Rede" <?php echo $filtros['tipo_problema'] === 'Queda de Rede' ? 'selected' : ''; ?>>Queda de Rede</option>
                    <option value="Intermitência" <?php echo $filtros['tipo_problema'] === 'Intermitência' ? 'selected' : ''; ?>>Intermitência</option>
                    <option value="Sem Conexão" <?php echo $filtros['tipo_problema'] === 'Sem Conexão' ? 'selected' : ''; ?>>Sem Conexão</option>
                    <option value="Problema de Equipamento" <?php echo $filtros['tipo_problema'] === 'Problema de Equipamento' ? 'selected' : ''; ?>>Problema de Equipamento</option>
                    <option value="Outros" <?php echo $filtros['tipo_problema'] === 'Outros' ? 'selected' : ''; ?>>Outros</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="localidade" class="form-label">Localidade</label>
                <input type="text" class="form-control" id="localidade" name="localidade" 
                       placeholder="Buscar localidade..." value="<?php echo htmlspecialchars($filtros['localidade']); ?>">
            </div>

            <div class="col-md-3">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-primary-custom">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="listar.php" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- LISTAGEM -->
<div class="card">
    <div class="card-header card-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Chamados (<?php echo count($chamados); ?>)
            </h5>
            <a href="criar.php" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Novo Chamado
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Localidade</th>
                    <th>IP</th>
                    <th>Tipo de Problema</th>
                    <th>Status</th>
                    <th>Data de Abertura</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($chamados)): ?>
                    <?php foreach ($chamados as $index => $chamado): ?>
                        <tr>
                            <td><?php echo $chamado['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($chamado['localidade']); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo htmlspecialchars($chamado['cidade']); ?></small>
                            </td>
                            <td><code><?php echo htmlspecialchars($chamado['ip']); ?></code></td>
                            <td><?php echo htmlspecialchars($chamado['tipo_problema']); ?></td>
                            <td>
                                <?php 
                                $status_cor = $chamado['status'] === 'Aberto' ? 'danger' : 'success';
                                $status_icon = $chamado['status'] === 'Aberto' ? 'fa-circle' : 'fa-check-circle';
                                ?>
                                <span class="badge bg-<?php echo $status_cor; ?>">
                                    <i class="fas <?php echo $status_icon; ?>"></i> 
                                    <?php echo htmlspecialchars($chamado['status']); ?>
                                </span>
                            </td>
                            <td>
                                <small><?php echo formatarDataHora($chamado['data_abertura']); ?></small>
                            </td>
                            <td>
                                <a href="detalhar.php?id=<?php echo $chamado['id']; ?>" 
                                   class="btn btn-sm btn-info" title="Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="editar.php?id=<?php echo $chamado['id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($chamado['status'] === 'Aberto'): ?>
                                    <a href="fechar.php?id=<?php echo $chamado['id']; ?>" 
                                       class="btn btn-sm btn-success" title="Fechar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="confirmarDelecao(<?php echo $chamado['id']; ?>)" 
                                        title="Deletar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum chamado encontrado</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmarDelecao(id) {
    if (confirm('Deseja realmente deletar este chamado?')) {
        window.location.href = 'deletar.php?id=' + id + '&confirm=true';
    }
}
</script>