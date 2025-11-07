<div class="page-title">
    <i class="fas fa-eye"></i> Detalhes do Ponto de Internet
</div>

<div class="row">
    <!-- Card Principal -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-building"></i> Informações do Ponto
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Localidade</h6>
                        <p class="fs-5 fw-bold"><?php echo htmlspecialchars($ponto['localidade']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Cidade</h6>
                        <p class="fs-5 fw-bold"><?php echo htmlspecialchars($ponto['cidade']); ?></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Território</h6>
                        <p><?php echo htmlspecialchars($ponto['territorio']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Endereço</h6>
                        <p><?php echo htmlspecialchars($ponto['endereco']); ?></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Velocidade</h6>
                        <p>
                            <span class="badge bg-info badge-custom">
                                <?php echo htmlspecialchars($ponto['velocidade']); ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Tipo</h6>
                        <p>
                            <span class="badge bg-primary badge-custom">
                                <?php echo htmlspecialchars($ponto['tipo'] ?? 'N/A'); ?>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Data de Instalação</h6>
                        <p><?php echo formatarData($ponto['data_instalacao']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Última Atualização</h6>
                        <p><?php echo formatarDataHora($ponto['data_atualizacao']); ?></p>
                    </div>
                </div>

                <?php if (!empty($ponto['observacao'])): ?>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted">Observações</h6>
                            <div class="bg-light p-3 rounded">
                                <p><?php echo nl2br(htmlspecialchars($ponto['observacao'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card de Localização -->
        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt"></i> Localização
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Latitude: <code><?php echo htmlspecialchars($ponto['latitude']); ?></code> | 
                    Longitude: <code><?php echo htmlspecialchars($ponto['longitude']); ?></code>
                </p>
                <div id="mapa" style="height: 400px; border-radius: 0.5rem;"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar com Ações -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-cog"></i> Ações
                </h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="editar.php?id=<?php echo $ponto['id']; ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <button class="btn btn-danger" onclick="confirmarDelecao(<?php echo $ponto['id']; ?>)">
                    <i class="fas fa-trash"></i> Deletar
                </button>
                <a href="listar.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle"></i> Informações
                </h5>
            </div>
            <div class="card-body text-muted" style="font-size: 0.875rem;">
                <p><strong>ID:</strong> <?php echo $ponto['id']; ?></p>
                <p><strong>Criado em:</strong> <?php echo formatarDataHora($ponto['data_criacao']); ?></p>
                <p><strong>Atualizado em:</strong> <?php echo formatarDataHora($ponto['data_atualizacao']); ?></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
// Inicializar mapa
const lat = <?php echo $ponto['latitude']; ?>;
const lng = <?php echo $ponto['longitude']; ?>;
const mapa = L.map('mapa').setView([lat, lng], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(mapa);

L.circleMarker([lat, lng], {
    radius: 10,
    fillColor: '#667eea',
    color: '#764ba2',
    weight: 2,
    opacity: 0.8,
    fillOpacity: 0.7
}).addTo(mapa).bindPopup('<strong><?php echo htmlspecialchars($ponto['localidade']); ?></strong><br><?php echo htmlspecialchars($ponto['cidade']); ?>');

function confirmarDelecao(id) {
    if (confirm('Deseja realmente deletar este ponto? Esta ação não pode ser desfeita.')) {
        window.location.href = 'deletar.php?id=' + id + '&confirm=true';
    }
}
</script>