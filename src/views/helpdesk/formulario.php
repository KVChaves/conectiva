<!--formulario.php-->
<div class="page-title">
    <i class="fas fa-<?php echo $edicao ? 'edit' : 'plus'; ?>"></i> 
    <?php echo $edicao ? 'Editar Chamado' : 'Criar Novo Chamado'; ?>
</div>

<div class="card">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-ticket-alt"></i> Dados do Chamado
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo gerarTokenCsrf(); ?>">
            
            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger alert-custom" role="alert">
                    <h5 class="alert-heading">
                        <i class="fas fa-exclamation-circle"></i> Erros no Formulário
                    </h5>
                    <ul class="mb-0">
                        <?php foreach ($erros as $campo => $erro): ?>
                            <li><?php echo htmlspecialchars($erro); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Ponto de Internet -->
                <div class="col-md-6 mb-3">
                    <label for="ponto_id" class="form-label">Ponto de Internet *</label>
                    <select class="form-select <?php echo isset($erros['ponto_id']) ? 'is-invalid' : ''; ?>" 
                            id="ponto_id" name="ponto_id" required>
                        <option value="">-- Selecione um Ponto --</option>
                        <?php 
                        $sql = "SELECT id, localidade, cidade, ip FROM conectiva ORDER BY localidade";
                        $result = $pdo->query($sql);
                        $pontos = $result->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($pontos as $ponto): 
                        ?>
                            <option value="<?php echo $ponto['id']; ?>" 
                                    <?php echo (isset($chamado) && $chamado['ponto_id'] == $ponto['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars("{$ponto['localidade']} - {$ponto['cidade']} ({$ponto['ip']})"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($erros['ponto_id'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['ponto_id']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Tipo de Problema -->
                <div class="col-md-6 mb-3">
                    <label for="tipo_problema" class="form-label">Tipo de Problema *</label>
                    <select class="form-select <?php echo isset($erros['tipo_problema']) ? 'is-invalid' : ''; ?>" 
                            id="tipo_problema" name="tipo_problema" required>
                        <option value="">-- Selecione um Tipo --</option>
                        <option value="Lentidão" <?php echo (isset($chamado) && $chamado['tipo_problema'] === 'Lentidão') ? 'selected' : ''; ?>>Lentidão</option>
                        <option value="Rompimento de Fibra" <?php echo (isset($chamado) && $chamado['tipo_problema'] === 'Rompimento de Fibra') ? 'selected' : ''; ?>>Rompimento de Fibra</option>
                        <option value="Queda de Rede" <?php echo (isset($chamado) && $chamado['tipo_problema'] === 'Queda de Rede') ? 'selected' : ''; ?>>Queda de Rede</option>
                        <option value="Intermitência" <?php echo (isset($chamado) && $chamado['tipo_problema'] === 'Intermitência') ? 'selected' : ''; ?>>Intermitência</option>
                        <option value="Sem Conexão" <?php echo (isset($chamado) && $chamado['tipo_problema'] === 'Sem Conexão') ? 'selected' : ''; ?>>Sem Conexão</option>
                        <option value="Problema de Equipamento" <?php echo (isset($chamado) && $chamado['tipo_problema'] === 'Problema de Equipamento') ? 'selected' : ''; ?>>Problema de Equipamento</option>
                        <option value="Outros" <?php echo (isset($chamado) && $chamado['tipo_problema'] === 'Outros') ? 'selected' : ''; ?>>Outros</option>
                    </select>
                    <?php if (isset($erros['tipo_problema'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['tipo_problema']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select <?php echo isset($erros['status']) ? 'is-invalid' : ''; ?>" 
                            id="status" name="status" required>
                        <option value="Aberto" <?php echo (isset($chamado) && $chamado['status'] === 'Aberto') ? 'selected' : 'selected'; ?>>Aberto</option>
                        <option value="Fechado" <?php echo (isset($chamado) && $chamado['status'] === 'Fechado') ? 'selected' : ''; ?>>Fechado</option>
                    </select>
                    <?php if (isset($erros['status'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['status']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Data de Fechamento (se aplicável) -->
                <?php if ($edicao && isset($chamado) && $chamado['status'] === 'Fechado'): ?>
                    <div class="col-md-6 mb-3">
                        <label for="data_fechamento" class="form-label">Data de Fechamento</label>
                        <input type="datetime-local" class="form-control" id="data_fechamento" name="data_fechamento"
                               value="<?php echo isset($chamado['data_fechamento']) ? str_replace(' ', 'T', substr($chamado['data_fechamento'], 0, 16)) : ''; ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-primary-custom">
                    <i class="fas fa-save"></i> <?php echo $edicao ? 'Atualizar' : 'Criar'; ?>
                </button>
                <a href="listar.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </form>
    </div>
</div>