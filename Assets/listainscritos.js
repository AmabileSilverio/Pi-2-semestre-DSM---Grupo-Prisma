// Função para carregar as inscrições
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

// Função para atualizar a tabela com as inscrições
function atualizarTabela(inscricoes) {
    const tbody = document.querySelector('.results-table table tbody');
    if (!tbody) {
        console.error('Elemento tbody não encontrado');
        return;
    }
    
    tbody.innerHTML = '';

    if (!inscricoes || inscricoes.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="5" class="text-center">Nenhuma inscrição encontrada</td>';
        tbody.appendChild(tr);
        return;
    }

    inscricoes.forEach(inscricao => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${inscricao.id}</td>
            <td>${inscricao.nome_professor}</td>
            <td>${formatarTipoHAE(inscricao.tipo_hae)}</td>
            <td><span class="status-badge ${inscricao.status.toLowerCase().replace(' ', '-')}">${inscricao.status}</span></td>
            <td>
                <button class="btn-action" onclick="viewProjectSummary(${inscricao.id})">
                    <i class="fas fa-eye"></i> Ver Projeto
                </button>
                ${inscricao.status === 'Pendente' ? `
                    <button class="btn-action approve" onclick="openApproveModal(${inscricao.id})">
                        <i class="fas fa-check"></i> Aprovar
                    </button>
                    <button class="btn-action reject" onclick="openRejectModal(${inscricao.id})">
                        <i class="fas fa-times"></i> Recusar
                    </button>
                ` : ''}
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Função para formatar o tipo de HAE
function formatarTipoHAE(tipo) {
    const tipos = {
        'estagio_supervisionado': 'Estágio Supervisionado',
        'trabalho_graduacao': 'Trabalho de Graduação',
        'iniciacao_cientifica': 'Iniciação Científica',
        'divulgacao_cursos': 'Divulgação dos Cursos',
        'administracao_academica': 'Administração Acadêmica',
        'enade': 'Preparação para ENADE'
    };
    return tipos[tipo] || tipo;
}

// Função para visualizar o resumo do projeto
function viewProjectSummary(id) {
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
                        <p><strong>Nome:</strong> ${inscricao.nome}</p>
                        <p><strong>E-mail:</strong> ${inscricao.email}</p>
                        <p><strong>RG:</strong> ${inscricao.rg}</p>
                        <p><strong>Contrato de Trabalho:</strong> ${inscricao.tipo_contrato}</p>
                        <p><strong>Contato:</strong> ${inscricao.contato}</p>
                        <p><strong>Número de Matrícula:</strong> ${inscricao.matricula}</p>
                        <p><strong>Possui aula em outra Fatec:</strong> ${inscricao.aula_outra_fatec}</p>
                        <p><strong>Horas Semanais Disponíveis:</strong> ${inscricao.horas_disponiveis}</p>
                    </div>

                    <div class="project-info">
                        <h3>Informações do Projeto</h3>
                        <p><strong>Título:</strong> ${inscricao.titulo_projeto}</p>
                        <p><strong>Projeto de Interesse da Unidade:</strong> ${inscricao.projeto_unidade}</p>
                        <p><strong>Tipo HAE:</strong> ${formatarTipoHAE(inscricao.tipo_hae)}</p>
                        <p><strong>Status:</strong> ${inscricao.status || 'Pendente'}</p>
                        <p><strong>Horas Solicitadas:</strong> ${inscricao.horas_solicitadas}</p>
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
                                <a href="${inscricao.proposta_path}" class="btn-download" target="_blank">
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

// Função para formatar os horários
function formatarHorarios(horarios) {
    try {
        const horariosObj = JSON.parse(horarios);
        let resultado = '';
        for (const [dia, tempos] of Object.entries(horariosObj)) {
            if (tempos[0] && tempos[1]) {
                resultado += `
                    <div class="horario-item">
                        <span class="horario-dia">${dia}</span>
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

// Função para fechar o modal
function closeProjectSummary() {
    const modal = document.getElementById('projectSummaryModal');
    modal.style.display = 'none';
}

// Função para imprimir o resumo
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

// Função para baixar o PDF
function downloadProjectSummary() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Configurar fonte e tamanho
    doc.setFont("helvetica", "normal");
    doc.setFontSize(12);
    
    // Pegar o conteúdo do modal
    const sections = document.querySelectorAll('.project-section');
    
    let y = 20;
    sections.forEach(section => {
        const title = section.querySelector('h3').textContent;
        const info = section.querySelector('.project-info');
        const paragraphs = info.querySelectorAll('p');
        
        // Adicionar título da seção
        doc.setFontSize(14);
        doc.setTextColor(156, 0, 0); // Cor vermelha
        doc.text(title, 20, y);
        y += 10;
        
        // Adicionar informações
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0); // Cor preta
        paragraphs.forEach(p => {
            const text = p.textContent;
            if (y > 270) { // Nova página se necessário
                doc.addPage();
                y = 20;
            }
            // Dividir texto longo em múltiplas linhas
            const splitText = doc.splitTextToSize(text, 170);
            doc.text(splitText, 20, y);
            y += 7 * splitText.length;
        });
        
        y += 10; // Espaço entre seções
    });
    
    // Adicionar cabeçalho
    doc.setFontSize(16);
    doc.setTextColor(156, 0, 0);
    doc.text("FATEC ITAPIRA", 105, 15, { align: "center" });
    doc.text("Detalhes da Inscrição", 105, 25, { align: "center" });
    
    // Adicionar rodapé
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

// Adicionar event listener para o formulário de filtro
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            carregarInscricoes();
        });
    }
    
    // Carregar inscrições ao iniciar a página
    carregarInscricoes();
});

// Funções para o modal de aprovação
function openApproveModal(id) {
    const modal = document.getElementById('approveProjectModal');
    const inscricaoId = document.getElementById('approveProjectForm');
    inscricaoId.dataset.id = id;
    
    // Carregar dados da inscrição
    fetch(`/Pi2/Controller/buscar_inscricao.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                const inscricao = data.data;
                document.getElementById('approveProjectTitle').textContent = inscricao.titulo_projeto;
                document.getElementById('approveProjectProfessor').textContent = inscricao.nome;
                document.getElementById('approveProjectUnit').textContent = inscricao.projeto_unidade;
                document.getElementById('approveProjectHours').textContent = inscricao.horas_solicitadas;
                
                // Definir data mínima como hoje
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('startDate').min = today;
                document.getElementById('endDate').min = today;
                
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

function closeApproveModal() {
    const modal = document.getElementById('approveProjectModal');
    modal.style.display = 'none';
    document.getElementById('approveProjectForm').reset();
}

function closeConfirmationModal() {
    const modal = document.getElementById('confirmationModal');
    modal.style.display = 'none';
}

function approveProject() {
    const form = document.getElementById('approveProjectForm');
    const id = form.dataset.id;
    const approvedHours = document.getElementById('approvedHours').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!approvedHours || !startDate || !endDate) {
        alert('Por favor, preencha todos os campos.');
        return;
    }
    
    const data = {
        id: id,
        status: 'Aprovado',
        horas_aprovadas: approvedHours,
        data_inicio: startDate,
        data_fim: endDate
    };
    
    fetch('/Pi2/Controller/atualizar_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            closeApproveModal();
            const confirmationModal = document.getElementById('confirmationModal');
            confirmationModal.style.display = 'flex';
            carregarInscricoes();
        } else {
            alert('Erro ao aprovar projeto: ' + data.mensagem);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao aprovar projeto. Por favor, tente novamente.');
    });
}

// Funções para o modal de rejeição
function openRejectModal(id) {
    const modal = document.getElementById('rejectProjectModal');
    const inscricaoId = document.getElementById('rejectProjectForm');
    inscricaoId.dataset.id = id;
    
    // Carregar dados da inscrição
    fetch(`/Pi2/Controller/buscar_inscricao.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                const inscricao = data.data;
                document.getElementById('rejectProjectTitle').textContent = inscricao.titulo_projeto;
                document.getElementById('rejectProjectProfessor').textContent = inscricao.nome;
                document.getElementById('rejectProjectUnit').textContent = inscricao.projeto_unidade;
                document.getElementById('rejectProjectHours').textContent = inscricao.horas_solicitadas || '';
                
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

function closeRejectModal() {
    const modal = document.getElementById('rejectProjectModal');
    modal.style.display = 'none';
    document.getElementById('rejectProjectForm').reset();
}

function closeRejectConfirmationModal() {
    const modal = document.getElementById('rejectConfirmationModal');
    modal.style.display = 'none';
}

function rejectProject() {
    const form = document.getElementById('rejectProjectForm');
    const id = form.dataset.id;
    const motivo = document.getElementById('rejectReason').value;
    
    if (!motivo) {
        alert('Por favor, informe o motivo da rejeição.');
        return;
    }
    
    const data = {
        id: id,
        status: 'Rejeitado',
        motivo_rejeicao: motivo
    };
    
    fetch('/Pi2/Controller/atualizar_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            closeRejectModal();
            const confirmationModal = document.getElementById('rejectConfirmationModal');
            confirmationModal.style.display = 'flex';
            carregarInscricoes();
        } else {
            alert('Erro ao rejeitar projeto: ' + data.mensagem);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao rejeitar projeto. Por favor, tente novamente.');
    });
}

// Fechar o modal se clicar fora dele
window.onclick = function(event) {
  const modals = document.querySelectorAll('.modal');
  modals.forEach(modal => {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  });
}