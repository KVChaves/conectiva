<div class="page-title">
    <i class="fas fa-<?php echo $edicao ? 'edit' : 'plus'; ?>"></i> 
    <?php echo $edicao ? 'Editar Ponto de Internet' : 'Criar Novo Ponto de Internet'; ?>
</div>

<div class="card">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-form"></i> Dados do Ponto
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
                <!-- Localidade -->
                <div class="col-md-6 mb-3">
                    <label for="localidade" class="form-label">Localidade *</label>
                    <input 
                        type="text" 
                        class="form-control <?php echo isset($erros['localidade']) ? 'is-invalid' : ''; ?>" 
                        id="localidade" 
                        name="localidade" 
                        placeholder="Ex: Escritório de Cansação"
                        value="<?php echo htmlspecialchars($ponto['localidade'] ?? $_POST['localidade'] ?? ''); ?>"
                        required
                    >
                    <?php if (isset($erros['localidade'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['localidade']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Endereço -->
                <div class="col-md-6 mb-3">
                    <label for="endereco" class="form-label">Endereço *</label>
                    <input 
                        type="text" 
                        class="form-control <?php echo isset($erros['endereco']) ? 'is-invalid' : ''; ?>" 
                        id="endereco" 
                        name="endereco" 
                        placeholder="Ex: Rua Principal, 123"
                        value="<?php echo htmlspecialchars($ponto['endereco'] ?? $_POST['endereco'] ?? ''); ?>"
                        required
                    >
                    <?php if (isset($erros['endereco'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['endereco']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Território -->
                <div class="col-md-6 mb-3">
                    <label for="territorio" class="form-label">Território *</label>
                    <select class="form-select <?php echo isset($erros['territorio']) ? 'is-invalid' : ''; ?>" 
                            id="territorio" name="territorio" required>
                        <option value="">-- Selecione um Território --</option>
                        <?php 
                        $territorio_selecionado = $ponto['territorio'] ?? $_POST['territorio'] ?? '';
                        foreach (array_keys($GLOBALS['territorios']) as $terr): 
                        ?>
                            <option value="<?php echo htmlspecialchars($terr); ?>" 
                                    <?php echo ($territorio_selecionado === $terr) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($terr); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($erros['territorio'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['territorio']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Cidade -->
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade *</label>
                    <select class="form-select <?php echo isset($erros['cidade']) ? 'is-invalid' : ''; ?>" 
                            id="cidade" name="cidade" required>
                        <option value="">-- Selecione uma Cidade --</option>
                        <?php 
                        // Listar todas as cidades de todos os territórios
                        $todas_cidades = [];
                        foreach ($GLOBALS['territorios'] as $cidades) {
                            $todas_cidades = array_merge($todas_cidades, $cidades);
                        }
                        // Remover duplicatas e ordenar
                        $todas_cidades = array_unique($todas_cidades);
                        sort($todas_cidades);
                        
                        $cidade_selecionada = $ponto['cidade'] ?? $_POST['cidade'] ?? '';
                        foreach ($todas_cidades as $cid): 
                        ?>
                            <option value="<?php echo htmlspecialchars($cid); ?>" 
                                    <?php echo ($cidade_selecionada === htmlspecialchars($cid)) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cid); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($erros['cidade'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['cidade']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Latitude -->
                <div class="col-md-3 mb-3">
                    <label for="latitude" class="form-label">Latitude *</label>
                    <input 
                        type="number" 
                        class="form-control <?php echo isset($erros['latitude']) ? 'is-invalid' : ''; ?>" 
                        id="latitude" 
                        name="latitude" 
                        placeholder="-12.9714"
                        step="0.000001"
                        value="<?php echo htmlspecialchars($ponto['latitude'] ?? $_POST['latitude'] ?? ''); ?>"
                        required
                    >
                    <?php if (isset($erros['latitude'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['latitude']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Longitude -->
                <div class="col-md-3 mb-3">
                    <label for="longitude" class="form-label">Longitude *</label>
                    <input 
                        type="number" 
                        class="form-control <?php echo isset($erros['longitude']) ? 'is-invalid' : ''; ?>" 
                        id="longitude" 
                        name="longitude" 
                        placeholder="-38.5014"
                        step="0.000001"
                        value="<?php echo htmlspecialchars($ponto['longitude'] ?? $_POST['longitude'] ?? ''); ?>"
                        required
                    >
                    <?php if (isset($erros['longitude'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['longitude']; ?></div>
                    <?php endif; ?>
                </div>


                <!-- Velocidade -->
                <div class="col-md-2 mb-2">
                <label for="velocidade" class="form-label">Velocidade *</label>
                <select class="form-select <?php echo isset($erros['velocidade']) ? 'is-invalid' : ''; ?>" 
                        id="velocidade" name="velocidade" required>
                    <option value="">-- Selecione --</option>
                    <?php 
                    $velocidade_selecionada = $ponto['velocidade'] ?? $_POST['velocidade'] ?? '';
                    foreach (VELOCIDADES_PADRAO as $vel): 
                    ?>
                        <option value="<?php echo htmlspecialchars($vel); ?>" 
                                <?php echo ($velocidade_selecionada === htmlspecialchars($vel)) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($vel); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($erros['velocidade'])): ?>
                    <div class="invalid-feedback"><?php echo $erros['velocidade']; ?></div>
                <?php endif; ?>
            </div>

                <!-- Tipo -->
                <div class="col-md-2 mb-2">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo">
                    <option value="">-- Selecione --</option>
                    <?php 
                    $tipo_selecionado = $ponto['tipo'] ?? $_POST['tipo'] ?? '';
                    foreach (TIPOS_CONEXAO as $tipo): 
                    ?>
                        <option value="<?php echo htmlspecialchars($tipo); ?>" 
                                <?php echo ($tipo_selecionado === htmlspecialchars($tipo)) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipo); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

                <!-- Data de Instalação -->
                <div class="col-md-2 mb-1">
                    <label for="data_instalacao" class="form-label">Data de Instalação *</label>
                    <input 
                        type="date" 
                        class="form-control <?php echo isset($erros['data_instalacao']) ? 'is-invalid' : ''; ?>" 
                        id="data_instalacao" 
                        name="data_instalacao" 
                        value="<?php echo htmlspecialchars($ponto['data_instalacao'] ?? $_POST['data_instalacao'] ?? ''); ?>"
                        required
                    >
                    <?php if (isset($erros['data_instalacao'])): ?>
                        <div class="invalid-feedback"><?php echo $erros['data_instalacao']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Observação -->
                <div class="col-12 mb-3">
                    <label for="observacao" class="form-label">Observação</label>
                    <textarea 
                        class="form-control" 
                        id="observacao" 
                        name="observacao" 
                        rows="4" 
                        placeholder="Adicione observações sobre este ponto..."
                    ><?php echo htmlspecialchars($ponto['observacao'] ?? $_POST['observacao'] ?? ''); ?></textarea>
                </div>
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
