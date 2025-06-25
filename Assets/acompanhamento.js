document.addEventListener('DOMContentLoaded', function() {
    // Carregar inscrições ao iniciar a página
    carregarInscricoes();

    // Adicionar listener para o formulário de filtros
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            carregarInscricoes();
        });
    }
});

function carregarInscricoes() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();

    // Adicionar parâmetros do formulário
    for (const [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }

    // Fazer a requisição para buscar as inscrições
    fetch(`/Pi2/Controller/buscar_inscricoes.php?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                atualizarTabela(data.data);
            } else {
                console.error('Erro ao carregar inscrições:', data.mensagem);
                alert('Erro ao carregar inscrições: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar inscrições. Por favor, tente novamente.');
        });
}

function atualizarTabela(inscricoes) {
    const tbody = document.querySelector('#resultsTable tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    if (!inscricoes || inscricoes.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="6" class="text-center">Nenhuma inscrição encontrada</td>';
        tbody.appendChild(tr);
        return;
    }

    inscricoes.forEach(inscricao => {
        const tr = document.createElement('tr');
        let botoes = `
            <button class="btn-action" onclick="viewProjectSummary(${inscricao.id})">
                <i class="fas fa-eye"></i> Ver Resumo
            </button>
        `;

        // Adiciona o botão de relatório final se o status for "Aprovado" ou "Em andamento"
        if (inscricao.status === 'Aprovado' || inscricao.status === 'Em andamento') {
            botoes += `
  <button class="btn-relatorio" onclick="abrirRelatorioModal(${inscricao.id})">
    Enviar Relatório Final
  </button>
`;
        }

        tr.innerHTML = `
            <td>${inscricao.id}</td>
            <td>${inscricao.nome_professor}</td>
            <td>${formatarTipoHAE(inscricao.tipo_hae)}</td>
            <td>${inscricao.projeto_unidade}</td>
            <td>
                <span class="status-badge ${inscricao.status.toLowerCase().replace(' ', '-')}">
                    ${inscricao.status}
                </span>
            </td>
            <td>
                ${botoes}
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function formatarTipoHAE(tipo) {
    const tipos = {
        'ic': 'Iniciação Científica',
        'es': 'Estágio Supervisionado',
        'pi': 'Projeto Integrador',
        'trabalho_graduacao': 'Trabalho de Graduação',
        'estagio_supervisionado': 'Estágio Supervisionado',
        'iniciacao_cientifica': 'Iniciação Científica',
        'divulgacao_cursos': 'Divulgação dos Cursos',
        'administracao_academica': 'Administração Acadêmica',
        'enade': 'Preparação para ENADE'
    };
    return tipos[tipo] || tipo;
}

function viewProjectSummary(id) {
    console.log('ID enviado para buscar_inscricao:', id);
    fetch(`/Pi2/Controller/buscar_inscricao.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                const inscricao = data.data;
                const modal = document.getElementById('projectSummaryModal');
                const content = document.getElementById('projectSummaryContent');
                
                content.innerHTML = `
<div class="project-info">
    <h3>Informações do Professor</h3>
    <p><strong>Nome:</strong> ${inscricao.nome_professor || inscricao.nome}</p>
    <p><strong>E-mail:</strong> ${inscricao.email}</p>
    <p><strong>RG:</strong> ${inscricao.rg}</p>
    <p><strong>Contrato de Trabalho:</strong> ${
      inscricao.tipo_contrato
        ? inscricao.tipo_contrato.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
        : ''
    }</p>
    <p><strong>Contato:</strong> ${inscricao.contato}</p>
    <p><strong>Número de Matrícula:</strong> ${inscricao.matricula}</p>
    <p><strong>Possui aula em outra Fatec:</strong> ${inscricao.aula_outra_fatec}</p>
    <p><strong>Horas Semanais Disponíveis:</strong> ${inscricao.horas_disponiveis}</p>
</div>

<div class="project-info">
    <h3>Informações do Projeto</h3>
    <p><strong>Nº do Edital:</strong> ${inscricao.id_edital}</p>
    <p><strong>Título:</strong> ${inscricao.titulo_projeto}</p>
    <p><strong>Projeto de Interesse da Unidade:</strong> ${inscricao.projeto_unidade}</p>
    <p><strong>Data de Início do Edital:</strong> ${inscricao.data_inicio ? inscricao.data_inicio.split('-').reverse().join('/') : ''}</p>
    <p><strong>Data de Término do Edital:</strong> ${inscricao.data_termino ? inscricao.data_termino.split('-').reverse().join('/') : ''}</p>
    <p><strong>Tipo HAE:</strong> ${formatarTipoHAE(inscricao.tipo_hae)}</p>
    <p><strong>Status:</strong> ${inscricao.status || 'Pendente'}</p>
    <p><strong>Horas Solicitadas:</strong> ${inscricao.horas_solicitadas}</p>
    ${inscricao.status === 'Aprovado' && inscricao.horas_aprovadas ? `<p><strong>Horas Aprovadas:</strong> ${inscricao.horas_aprovadas}</p>` : ''}
    ${inscricao.status === 'Rejeitado' && inscricao.motivo_rejeicao ? `<p><strong>Motivo da Rejeição:</strong> ${inscricao.motivo_rejeicao}</p>` : ''}
</div>

<div class="project-summary">
        <h3>Descrição do Projeto</h3>
        <p>${inscricao.descricao}</p>
        <h3>Metodologia</h3>
        <p>${inscricao.metodologia}</p>
    </div>

    <div class="horarios-section">
        <h3>Horários do Projeto</h3>
        <div class="horarios-list">
            ${formatarHorarios(inscricao.horarios)}
        </div>
    </div>

    <div class="arquivo-section">
    <h3>Arquivo do Projeto</h3>
    <div class="arquivo-info">
        <p><strong>Proposta de Projeto:</strong> 
            <a href="/Pi2/uploads_inscricoes/${inscricao.anexo}" class="btn-download" target="_blank">
                <i class="fas fa-file-pdf"></i> Baixar PDF
            </a>
        </p>
    </div>
</div>
                `;
                
                modal.style.display = 'flex';
            } else {
                alert('Erro ao carregar detalhes: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar detalhes. Por favor, tente novamente.');
        });
}

function formatarHorarios(horarios) {
    try {
        const horariosObj = JSON.parse(horarios);
        let resultado = '';
        for (const [dia, tempos] of Object.entries(horariosObj)) {
            if (tempos[0] && tempos[1]) {
                resultado += `
                    <div class="horario-item">
                        <span class="horario-dia">${dia.charAt(0).toUpperCase() + dia.slice(1)}</span>
                        <span class="horario-periodo">${tempos[0]} até ${tempos[1]}</span>
                    </div>
                `;
            }
        }
        return resultado || '<p>Nenhum horário definido</p>';
    } catch (e) {
        return '<p>Nenhum horário definido</p>';
    }
}

function closeProjectSummary() {
    const modal = document.getElementById('projectSummaryModal');
    modal.style.display = 'none';
}

function printProjectSummary() {
    const content = document.getElementById('projectSummaryContent').innerHTML;
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Detalhes da Inscrição</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h3 { color: #9c0000; margin-top: 20px; }
                    .project-info { margin: 15px 0; }
                    .project-section { margin-bottom: 30px; }
                    p { margin: 8px 0; }
                </style>
            </head>
            <body>
                ${content}
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}

function downloadProjectSummary() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Cabeçalho
    doc.setFontSize(16);
    doc.setTextColor(156, 0, 0);
    doc.text("FATEC ITAPIRA", 105, 15, { align: "center" });
    doc.text("Detalhes da Inscrição", 105, 25, { align: "center" });

    // Pega o conteúdo do modal
    const content = document.getElementById('projectSummaryContent');
    let y = 35;

    // Para cada seção/título
    content.querySelectorAll('h3').forEach(h3 => {
        // Título em vermelho
        doc.setFontSize(14);
        doc.setTextColor(156, 0, 0);
        doc.text(h3.textContent, 10, y);
        y += 8;

        // Pega os <p> e <div> irmãos até o próximo <h3> ou fim
        let el = h3.nextElementSibling;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        while (el && el.tagName !== 'H3') {
            if (el.tagName === 'P') {
                const lines = doc.splitTextToSize(el.textContent, 180);
                doc.text(lines, 12, y);
                y += lines.length * 7;
            }
            // Adiciona os horários do projeto
            if (el.classList && el.classList.contains('horarios-list')) {
                el.querySelectorAll('.horario-item').forEach(item => {
                    const texto = item.innerText.trim();
                    const lines = doc.splitTextToSize(texto, 180);
                    doc.text(lines, 14, y);
                    y += lines.length * 7;
                });
            }
            el = el.nextElementSibling;
        }
        y += 3;
        if (y > 270) {
            doc.addPage();
            y = 20;
        }
    });

    // Rodapé
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text(
            `Página ${i} de ${pageCount}`,
            doc.internal.pageSize.width / 2,
            doc.internal.pageSize.height - 10,
            { align: "center" }
        );
    }

    doc.save('detalhes_inscricao.pdf');
}

function abrirRelatorioModal(idInscricao) {
  console.log('ID recebido:', idInscricao); // Mostra o valor recebido ao abrir o modal
  document.getElementById('relatorioIdInscricao').value = idInscricao;
  document.getElementById('descricaoRelatorio').value = '';
  document.getElementById('relatorioModal').style.display = 'flex';
}

function fecharRelatorioModal() {
  document.getElementById('relatorioModal').style.display = 'none';
}

function enviarRelatorioFinal() {
  console.log('Função enviarRelatorioFinal chamada');
  const form = document.getElementById('relatorioForm');
  const formData = new FormData(form);
  console.log('ID enviado:', formData.get('id_inscricao')); // Mostra o valor enviado ao backend

  fetch('/Pi2/Controller/enviar_relatorio.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'ok') {
      fecharRelatorioModal();
      abrirModalRelatorioEnviado();
      // location.reload() agora está no fecharModalRelatorioEnviado
    } else {
      alert('Erro ao enviar relatório: ' + (data && data.mensagem ? data.mensagem : ''));
    }
  })
  .catch(error => {
    alert('Erro ao enviar relatório.');
    console.error(error);
  });
}

// Fechar modal quando clicar fora dele
window.onclick = function(event) {
    const modal = document.getElementById('projectSummaryModal');
    if (event.target == modal) {
        closeProjectSummary();
    }
}

function abrirModalReenvio(idRelatorio) {
  document.getElementById('idRelatorioReenvio').value = idRelatorio;
  document.getElementById('descricaoReenvio').value = '';
  document.getElementById('anexoReenvio').value = '';
  document.getElementById('modalReenvio').style.display = 'flex';
}

function fecharModalReenvio() {
  document.getElementById('modalReenvio').style.display = 'none';
}

function enviarReenvioRelatorio() {
  const form = document.getElementById('formReenvio');
  const formData = new FormData(form);

  fetch('/Pi2/Controller/reenviar_relatorio.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'ok') {
      fecharModalReenvio();
      abrirModalRelatorioReenviado();
    } else {
      alert('Erro ao reenviar relatório: ' + (data && data.mensagem ? data.mensagem : ''));
    }
  })
  .catch(error => {
    alert('Erro ao reenviar relatório.');
    console.error(error);
  });
}

function abrirModalRelatorioEnviado() {
  document.getElementById('modalRelatorioEnviado').style.display = 'block';
}
function fecharModalRelatorioEnviado() {
  document.getElementById('modalRelatorioEnviado').style.display = 'none';
  location.reload(); // se quiser recarregar a página após fechar
}

function abrirModalRelatorioReenviado() {
  document.getElementById('modalRelatorioReenviado').style.display = 'flex';
}
function fecharModalRelatorioReenviado() {
  document.getElementById('modalRelatorioReenviado').style.display = 'none';
  location.reload(); // recarrega a página ao fechar o modal, se desejar
}
