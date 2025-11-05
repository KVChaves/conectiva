/**
 * Script principal da aplicação InternetBa
 */

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Validar formulários Bootstrap
    validarFormularios();
    
    // Adicionar tooltips
    inicializarTooltips();
    
    // Fechar alertas após 5 segundos
    fecharAlertasAutomaticos();
});

/**
 * Validar formulários com Bootstrap
 */
function validarFormularios() {
    const formularios = document.querySelectorAll('form.needs-validation');
    
    Array.from(formularios).forEach(formulario => {
        formulario.addEventListener('submit', function(event) {
            if (!formulario.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            formulario.classList.add('was-validated');
        }, false);
    });
}

/**
 * Inicializar tooltips do Bootstrap
 */
function inicializarTooltips() {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    Array.from(tooltips).forEach(tooltipElement => {
        new bootstrap.Tooltip(tooltipElement);
    });
}

/**
 * Fechar alertas automaticamente após 5 segundos
 */
function fecharAlertasAutomaticos() {
    const alertas = document.querySelectorAll('.alert');
    
    alertas.forEach(alerta => {
        // Se o alerta for de sucesso, fechar após 5 segundos
        if (alerta.classList.contains('alert-success')) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alerta);
                bsAlert.close();
            }, 5000);
        }
    });
}

/**
 * Confirmar exclusão
 */
function confirmarDelecao(id) {
    if (confirm('Deseja realmente deletar este ponto? Esta ação não pode ser desfeita.')) {
        window.location.href = 'deletar.php?id=' + id + '&confirm=true';
    }
}

/**
 * Confirmar ação genérica
 */
function confirmarAcao(mensagem, callback) {
    if (confirm(mensagem)) {
        if (typeof callback === 'function') {
            callback();
        }
    }
}

/**
 * Mostrar mensagem de sucesso
 */
function mostrarSucesso(mensagem) {
    mostrarNotificacao(mensagem, 'success');
}

/**
 * Mostrar mensagem de erro
 */
function mostrarErro(mensagem) {
    mostrarNotificacao(mensagem, 'danger');
}

/**
 * Mostrar mensagem de aviso
 */
function mostrarAviso(mensagem) {
    mostrarNotificacao(mensagem, 'warning');
}

/**
 * Mostrar notificação genérica
 */
function mostrarNotificacao(mensagem, tipo = 'info') {
    const mainContent = document.querySelector('.main-content');
    
    if (!mainContent) {
        alert(mensagem);
        return;
    }
    
    const tipoClasse = 'alert-' + tipo;
    const alerta = document.createElement('div');
    alerta.className = `alert ${tipoClasse} alert-dismissible fade show`;
    alerta.setAttribute('role', 'alert');
    alerta.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    mainContent.insertBefore(alerta, mainContent.firstChild);
    
    // Fechar automaticamente após 5 segundos se for sucesso
    if (tipo === 'success') {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        }, 5000);
    }
}

/**
 * Carregar cidades baseado no território
 */
function carregarCidades(territorioSelect, cidadeSelect) {
    const territorio = territorioSelect.value;
    const cidadeAtual = cidadeSelect.value;
    
    cidadeSelect.innerHTML = '<option value="">-- Selecione uma Cidade --</option>';
    
    if (territorio && window.territorios && window.territorios[territorio]) {
        window.territorios[territorio].forEach(cidade => {
            const option = document.createElement('option');
            option.value = cidade;
            option.textContent = cidade;
            if (cidade === cidadeAtual) {
                option.selected = true;
            }
            cidadeSelect.appendChild(option);
        });
    }
}

/**
 * Copiar para clipboard
 */
function copiarParaClipboard(texto, elemento) {
    navigator.clipboard.writeText(texto).then(() => {
        // Feedback visual
        const textoOriginal = elemento.innerHTML;
        elemento.innerHTML = '<i class="fas fa-check"></i> Copiado!';
        elemento.classList.add('btn-success');
        elemento.classList.remove('btn-secondary');
        
        setTimeout(() => {
            elemento.innerHTML = textoOriginal;
            elemento.classList.remove('btn-success');
            elemento.classList.add('btn-secondary');
        }, 2000);
    }).catch(err => {
        console.error('Erro ao copiar:', err);
        alert('Erro ao copiar para a área de transferência');
    });
}

/**
 * Formatar número como moeda
 */
function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

/**
 * Formatar data para exibição
 */
function formatarData(dataStr) {
    const data = new Date(dataStr);
    return data.toLocaleDateString('pt-BR');
}

/**
 * Validar email
 */
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Validar IP
 */
function validarIp(ip) {
    const regex = /^(\d{1,3}\.){3}\d{1,3}$/;
    if (!regex.test(ip)) return false;
    
    const partes = ip.split('.');
    return partes.every(parte => parseInt(parte) <= 255);
}

/**
 * Fazer requisição AJAX
 */
function fazerRequisicao(url, opcoes = {}) {
    const defaultOpcoes = {
        metodo: 'GET',
        dados: null,
        tipo: 'json',
        sucesso: function() {},
        erro: function() {},
        completo: function() {}
    };
    
    const config = { ...defaultOpcoes, ...opcoes };
    
    let init = {
        method: config.metodo,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    if (config.dados && config.metodo !== 'GET') {
        init.body = config.tipo === 'json' 
            ? JSON.stringify(config.dados)
            : new URLSearchParams(config.dados);
            
        if (config.tipo === 'json') {
            init.headers['Content-Type'] = 'application/json';
        }
    }
    
    fetch(url, init)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return config.tipo === 'json' ? response.json() : response.text();
        })
        .then(data => {
            config.sucesso(data);
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            config.erro(error);
        })
        .finally(() => {
            config.completo();
        });
}

/**
 * Limpar formulário
 */
function limparFormulario(formularioId) {
    const formulario = document.getElementById(formularioId);
    if (formulario) {
        formulario.reset();
        formulario.classList.remove('was-validated');
    }
}

/**
 * Desabilitar botão durante envio
 */
function desabilitarBotaoEnvio(botaoId) {
    const botao = document.getElementById(botaoId);
    if (botao) {
        botao.disabled = true;
        botao.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';
    }
}

/**
 * Habilitar botão após envio
 */
function habilitarBotaoEnvio(botaoId, texto) {
    const botao = document.getElementById(botaoId);
    if (botao) {
        botao.disabled = false;
        botao.innerHTML = texto;
    }
}

/**
 * Esconder elemento
 */
function esconder(elementoId) {
    const elemento = document.getElementById(elementoId);
    if (elemento) {
        elemento.style.display = 'none';
    }
}

/**
 * Mostrar elemento
 */
function mostrar(elementoId) {
    const elemento = document.getElementById(elementoId);
    if (elemento) {
        elemento.style.display = 'block';
    }
}

/**
 * Toggle visibilidade
 */
function toggle(elementoId) {
    const elemento = document.getElementById(elementoId);
    if (elemento) {
        elemento.style.display = elemento.style.display === 'none' ? 'block' : 'none';
    }
}

/**
 * Adicionar classe
 */
function adicionarClasse(elementoId, classe) {
    const elemento = document.getElementById(elementoId);
    if (elemento) {
        elemento.classList.add(classe);
    }
}

/**
 * Remover classe
 */
function removerClasse(elementoId, classe) {
    const elemento = document.getElementById(elementoId);
    if (elemento) {
        elemento.classList.remove(classe);
    }
}

/**
 * Verificar se navegador suporta geolocalização
 */
function temGeolocalizacao() {
    return 'geolocation' in navigator;
}

/**
 * Obter localização atual
 */
function obterLocalizacao(sucesso, erro) {
    if (!temGeolocalizacao()) {
        if (typeof erro === 'function') {
            erro('Geolocalização não suportada neste navegador');
        }
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        (position) => {
            if (typeof sucesso === 'function') {
                sucesso({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                });
            }
        },
        (error) => {
            if (typeof erro === 'function') {
                erro('Erro ao obter localização: ' + error.message);
            }
        }
    );
}