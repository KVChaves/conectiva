<div class="page-title">
    <i class="fas fa-map"></i> Mapa de Pontos de Internet - Bahia
</div>

<!-- FILTROS -->
<div class="filters">
    <input type="text" id="filtroSearchPontos" placeholder="Buscar por localidade, cidade ou IP...">
    <select id="filtroCidade">
        <option value="">-- Todas as Cidades --</option>
        <?php 
        $cidades = array_unique(array_column($pontos, 'cidade'));
        sort($cidades);
        foreach ($cidades as $cidade): 
        ?>
            <option value="<?php echo htmlspecialchars($cidade); ?>">
                <?php echo htmlspecialchars($cidade); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- LEGENDA -->
<div style="position: absolute; bottom: 20px; right: 20px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 1000; max-width: 250px;">
    <h6 style="margin: 0 0 10px 0; font-weight: bold;">Legenda</h6>
    <div style="display: flex; align-items: center; margin-bottom: 8px;">
        <div style="width: 24px; height: 24px; background-color: #10b981; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
        <span style="font-size: 12px;">Internet Cedida</span>
    </div>
    <div style="display: flex; align-items: center; margin-bottom: 8px;">
        <div style="width: 24px; height: 24px; background-color: #3b82f6; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
        <span style="font-size: 12px;">Internet Local</span>
    </div>
    <div style="display: flex; align-items: center; margin-bottom: 8px;">
        <div style="width: 24px; height: 24px; background-color: #f59e0b; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
        <span style="font-size: 12px;">Modem Vivo</span>
    </div>
    <div style="display: flex; align-items: center; margin-bottom: 8px;">
        <div style="width: 24px; height: 24px; background-color: #ef4444; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
        <span style="font-size: 12px;">Rede Governo</span>
    </div>
    <div style="display: flex; align-items: center;">
        <div style="width: 24px; height: 24px; background-color: #8b5cf6; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
        <span style="font-size: 12px;">Velox</span>
    </div>
</div>

<!-- MAPA -->
<div id="mapa"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
    console.log('Iniciando script do mapa...');
    console.log('PHP $pontos count: <?php echo count($pontos); ?>');
    
    // Gerar JSON de forma segura
    const pontosData = [
        <?php 
            if (!empty($pontos)) {
                $jsonItems = [];
                $errosEnconrados = 0;
                
                foreach ($pontos as $ponto) {
                    // Limpar e validar dados antes de criar o item
                    $item = [
                        'id' => (int)$ponto['id'],
                        'localidade' => isset($ponto['localidade']) ? trim((string)$ponto['localidade']) : '',
                        'territorio' => isset($ponto['territorio']) ? trim((string)$ponto['territorio']) : '',
                        'cidade' => isset($ponto['cidade']) ? trim((string)$ponto['cidade']) : '',
                        'endereco' => isset($ponto['endereco']) ? trim((string)$ponto['endereco']) : '',
                        'latitude' => isset($ponto['latitude']) && $ponto['latitude'] !== null && $ponto['latitude'] !== '' 
                            ? (float)str_replace(',', '.', $ponto['latitude']) 
                            : null,
                        'longitude' => isset($ponto['longitude']) && $ponto['longitude'] !== null && $ponto['longitude'] !== '' 
                            ? (float)str_replace(',', '.', $ponto['longitude']) 
                            : null,
                        'ip' => isset($ponto['ip']) ? trim((string)$ponto['ip']) : '',
                        'circuito' => isset($ponto['circuito']) ? trim((string)$ponto['circuito']) : '',
                        'velocidade' => isset($ponto['velocidade']) ? trim((string)$ponto['velocidade']) : '',
                        'tipo' => isset($ponto['tipo']) ? trim((string)$ponto['tipo']) : '',
                        'marcador' => isset($ponto['marcador']) ? trim((string)$ponto['marcador']) : '',
                        'data_instalacao' => isset($ponto['data_instalacao']) ? trim((string)$ponto['data_instalacao']) : '',
                        'observacao' => isset($ponto['observacao']) ? trim((string)$ponto['observacao']) : ''
                    ];
                    
                    // Tentar codificar em JSON
                    $jsonEncoded = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
                    
                    // Verificar se a codificação foi bem-sucedida
                    if ($jsonEncoded !== false && $jsonEncoded !== null) {
                        $jsonItems[] = $jsonEncoded;
                    } else {
                        // Logar erro para debug
                        $errosEnconrados++;
                        error_log("Erro ao codificar ponto ID {$ponto['id']}: " . json_last_error_msg());
                        
                        // Tentar limpar caracteres problemáticos e tentar novamente
                        foreach ($item as $key => $value) {
                            if (is_string($value)) {
                                $item[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                            }
                        }
                        
                        $jsonEncoded = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
                        if ($jsonEncoded !== false && $jsonEncoded !== null) {
                            $jsonItems[] = $jsonEncoded;
                        }
                    }
                }
                
                echo implode(",\n        ", $jsonItems);
            }
        ?>
    ];
    
    console.log('Total de pontos carregados:', pontosData.length);
    if (pontosData.length > 0) {
        console.log('Primeiro ponto:', pontosData[0]);
        
        // Contar tipos
        const tiposCount = {};
        pontosData.forEach(p => {
            const tipo = p.tipo || 'SEM TIPO';
            tiposCount[tipo] = (tiposCount[tipo] || 0) + 1;
        });
        console.log('Distribuição de tipos:', tiposCount);
    }

    // Inicializar mapa centrado na Bahia
    try {
        const mapa = L.map('mapa').setView([-12.9714, -38.5014], 7);

        // Adicionar camada de mapa
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapa);

        let marcadores = [];

        // Função para adicionar marcadores no mapa
        function adicionarMarcadores(dados) {
            // Remover marcadores existentes
            marcadores.forEach(m => mapa.removeLayer(m));
            marcadores = [];

            dados.forEach(ponto => {
                const lat = parseFloat(ponto.latitude);
                const lng = parseFloat(ponto.longitude);

                if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                    // Definir cor baseado no tipo de conexão
                    let cor = '#9ca3af'; // Cinza padrão
                    
                    if (ponto.tipo === 'Internet Cedida') {
                        cor = '#10b981'; // Verde
                    } else if (ponto.tipo === 'Internet Local') {
                        cor = '#3b82f6'; // Azul
                    } else if (ponto.tipo === 'Modem Vivo') {
                        cor = '#f59e0b'; // Amarelo
                    } else if (ponto.tipo === 'Rede Governo') {
                        cor = '#ef4444'; // Vermelho
                    } else if (ponto.tipo === 'Velox') {
                        cor = '#8b5cf6'; // Roxo
                    }
                    
                    // Criar círculo simples
                    const marcador = L.circleMarker([lat, lng], {
                        radius: 6,
                        fillColor: cor,
                        color: 'white',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    });

                    // Popup com informações do ponto
                    const popupContent = `
                        <div class="popup-content">
                            <h3 style="color: ${cor};">${ponto.localidade || 'N/A'}</h3>
                            <hr>
                            <p><strong>Cidade:</strong> ${ponto.cidade || 'N/A'}</p>
                            <p><strong>Endereço:</strong> ${ponto.endereco || 'Não informado'}</p>
                            <p><strong>IP:</strong> <code>${ponto.ip || 'N/A'}</code></p>
                            <p><strong>Velocidade:</strong> <span style="background: #e0e0e0; padding: 2px 6px; border-radius: 3px;">${ponto.velocidade || 'N/A'}</span></p>
                            <p><strong>Tipo:</strong> ${ponto.tipo || 'N/A'}</p>
                            <p><strong>Data Instalação:</strong> ${ponto.data_instalacao || 'N/A'}</p>
                            ${ponto.observacao ? `<p><strong>Observação:</strong> ${ponto.observacao}</p>` : ''}
                            <div class="btn-group">
                                <a href="src/Views/conectiva/detalhar.php?id=${ponto.id}" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                <a href="src/Views/conectiva/editar.php?id=${ponto.id}" class="btn btn-sm btn-warning">Editar</a>
                            </div>
                        </div>
                    `;

                    marcador.bindPopup(popupContent);
                    marcador.addTo(mapa);
                    marcadores.push(marcador);

                    // Abrir popup ao clicar
                    marcador.on('click', function() {
                        this.openPopup();
                    });
                }
            });
            
            console.log('Total de marcadores adicionados:', marcadores.length);
        }

        // Adicionar marcadores inicialmente
        adicionarMarcadores(pontosData);
        
        // Ajustar zoom para mostrar todos os marcadores
        if (marcadores.length > 0) {
            const group = new L.featureGroup(marcadores);
            mapa.fitBounds(group.getBounds().pad(0.1));
            console.log('Zoom ajustado para mostrar todos os marcadores');
        }

        // Filtros
        document.getElementById('filtroCidade').addEventListener('change', function() {
            const cidade = this.value;
            const busca = document.getElementById('filtroSearchPontos').value.toLowerCase();
            
            const filtrado = pontosData.filter(p => {
                const cidadeMatch = cidade === '' || p.cidade === cidade;
                const buscaMatch = busca === '' || 
                    p.localidade.toLowerCase().includes(busca) ||
                    p.cidade.toLowerCase().includes(busca) ||
                    p.ip.toLowerCase().includes(busca);
                return cidadeMatch && buscaMatch;
            });

            adicionarMarcadores(filtrado);
        });

        document.getElementById('filtroSearchPontos').addEventListener('keyup', function() {
            const busca = this.value.toLowerCase();
            const cidade = document.getElementById('filtroCidade').value;
            
            const filtrado = pontosData.filter(p => {
                const cidadeMatch = cidade === '' || p.cidade === cidade;
                const buscaMatch = busca === '' || 
                    p.localidade.toLowerCase().includes(busca) ||
                    p.cidade.toLowerCase().includes(busca) ||
                    p.ip.toLowerCase().includes(busca);
                return cidadeMatch && buscaMatch;
            });

            adicionarMarcadores(filtrado);
        });
        
        console.log('Mapa inicializado com sucesso!');
    } catch (error) {
        console.error('Erro ao inicializar mapa:', error);
        alert('Erro ao carregar o mapa: ' + error.message);
    }
</script>