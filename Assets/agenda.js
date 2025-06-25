// Função para carregar os projetos do professor
function carregarProjetos() {
    fetch('/Pi2/Controller/buscar_agenda_professor.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                atualizarTabela(data.data);
            } else {
                console.error('Erro ao carregar projetos:', data.mensagem);
                alert('Erro ao carregar projetos: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar projetos. Por favor, tente novamente.');
        });
}

// Função para atualizar a tabela com os projetos
function atualizarTabela(projetos) {
    const tbody = document.querySelector('.table-container table tbody');
    if (!tbody) {
        console.error('Elemento tbody não encontrado');
        return;
    }
    
    tbody.innerHTML = '';

    if (!projetos || projetos.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="6" class="text-center">Nenhum projeto encontrado</td>';
        tbody.appendChild(tr);
        return;
    }

    projetos.forEach(projeto => {
        let statusClass = '';
        if (projeto.status.toLowerCase() === 'em andamento' || projeto.status.toLowerCase() === 'em_andamento') {
            statusClass = 'progress';
        } else if (projeto.status.toLowerCase() === 'pendente') {
            statusClass = 'pending';
        } else if (projeto.status.toLowerCase() === 'concluído' || projeto.status.toLowerCase() === 'concluido') {
            statusClass = 'done';
        }
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${projeto.titulo_projeto}</td>
            <td>${projeto.data_inicio}</td>
            <td>${projeto.horario_do_dia || '-'}</td>
            <td>${projeto.data_fim}</td>
            <td><span class="status-badge ${statusClass}">${projeto.status}</span></td>
            <td>
                <button class="btn-action" onclick="viewProjectDetails(${projeto.id})">
                    <i class="fas fa-eye"></i> Detalhes
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Função para visualizar os detalhes do projeto
function viewProjectDetails(id) {
    fetch(`/Pi2/Controller/buscar_agenda_professor.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok' && data.data.length > 0) {
                const projeto = data.data[0];
                const modal = document.getElementById('projectDetailsModal');
                const content = document.getElementById('projectDetailsContent');

                content.innerHTML = `
                    <div class="project-info">
                        <h3>${projeto.titulo_projeto}</h3>
                        <p><strong>Tipo HAE:</strong> ${formatarTipoHAE(projeto.tipo_hae)}</p>
                        <p><strong>Status:</strong> ${projeto.status}</p>
                        <p><strong>Data de Início:</strong> ${projeto.data_inicio}</p>
                        <p><strong>Data de Término:</strong> ${projeto.data_fim}</p>
                        <p><strong>Horas Aprovadas:</strong> ${projeto.horas_aprovadas}</p>
                        <p><strong>Horário do Dia:</strong> ${projeto.horario_do_dia}</p>
                        <div>
                            <strong>Horários do Projeto:</strong>
                            <ul style="list-style: none; padding-left: 0;">
                                ${
                                    (() => {
                                        let html = '';
                                        if (projeto.horarios) {
                                            try {
                                                const horarios = JSON.parse(projeto.horarios);
                                                for (const dia in horarios) {
                                                    if (horarios[dia] && horarios[dia][0] && horarios[dia][1]) {
                                                        html += `<li>${dia.charAt(0).toUpperCase() + dia.slice(1)}: ${horarios[dia][0]} até ${horarios[dia][1]}</li>`;
                                                    }
                                                }
                                        } catch (e) {
                                            html = '<li>Não foi possível exibir os horários.</li>';
                                        }
                                    } else {
                                        html = '<li>Nenhum horário cadastrado.</li>';
                                    }
                                    return html;
                                })()
                            }
                            </ul>
                        </div>
                    </div>
                   
                `;

                modal.style.display = 'flex';
            } else {
                alert('Erro ao carregar detalhes do projeto');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar detalhes do projeto. Por favor, tente novamente.');
        });
}

function closeProjectDetails() {
    const modal = document.getElementById('projectDetailsModal');
    modal.style.display = 'none';
}

function printProjectDetails() {
    window.print();
}

function downloadProjectDetails() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Configurar fonte e tamanho
    doc.setFont("helvetica", "normal");
    doc.setFontSize(16);
    
    // Pegar o conteúdo do modal
    const title = document.querySelector('#projectDetailsContent h3').textContent;
    const type = document.querySelector('.project-info p:nth-child(2)').textContent;
    const status = document.querySelector('.project-info p:nth-child(3)').textContent;
    const dataInicio = document.querySelector('.project-info p:nth-child(4)').textContent;
    const horaInicio = document.querySelector('.project-info p:nth-child(5)').textContent;
    const dataFim = document.querySelector('.project-info p:nth-child(6)').textContent;
    const horasAprovadas = document.querySelector('.project-info p:nth-child(7)').textContent;
    const metodologia = document.querySelector('.project-summary p:nth-child(2)').textContent;
    const descricao = document.querySelector('.project-summary p:nth-child(4)').textContent;
    
    // Adicionar cabeçalho
    doc.setFillColor(156, 0, 0); // Cor vermelha da Fatec
    doc.rect(0, 0, 210, 30, 'F');
    doc.setTextColor(255, 255, 255);
    doc.text("FATEC ITAPIRA", 105, 15, { align: "center" });
    doc.text("Detalhes do Projeto", 105, 25, { align: "center" });
    
    // Resetar cor do texto
    doc.setTextColor(0, 0, 0);
    
    // Adicionar título
    doc.setFontSize(14);
    doc.text(title, 20, 45);
    
    // Adicionar informações do projeto
    doc.setFontSize(12);
    doc.text(type, 20, 60);
    doc.text(status, 20, 70);
    doc.text(dataInicio, 20, 80);
    doc.text(horaInicio, 20, 90);
    doc.text(dataFim, 20, 100);
    doc.text(horasAprovadas, 20, 110);
    
    // Adicionar metodologia
    doc.setFontSize(12);
    doc.text("Metodologia:", 20, 135);
    doc.setFontSize(10);
    const metodologiaLines = doc.splitTextToSize(metodologia, 170);
    doc.text(metodologiaLines, 20, 145);
    
    // Adicionar descrição
    doc.setFontSize(12);
    doc.text("Descrição:", 20, 165);
    doc.setFontSize(10);
    const descricaoLines = doc.splitTextToSize(descricao, 170);
    doc.text(descricaoLines, 20, 175);
    
    // Salvar o PDF
    doc.save('detalhes_projeto.pdf');
}

// Fechar o modal se clicar fora dele
window.onclick = function(event) {
    const modal = document.getElementById('projectDetailsModal');
    if (event.target == modal) {
        closeProjectDetails();
    }
}

// Carregar projetos ao iniciar a página
document.addEventListener('DOMContentLoaded', function() {
    carregarProjetos();
    
    // Adicionar listener para o formulário de filtros
    const filterForm = document.querySelector('.filters');
    if (filterForm) {
        const filterButton = filterForm.querySelector('.btn-primary');
        const clearButton = filterForm.querySelector('.btn-secondary');
        
        filterButton.addEventListener('click', function() {
            const status = document.getElementById('status').value;
            const data = document.getElementById('data').value;
            
            // Construir a URL com os parâmetros de filtro
            let url = '/Pi2/Controller/buscar_agenda_professor.php';
            const params = new URLSearchParams();
            
            if (status) {
                params.append('status', status);
            }
            
            if (data) {
                params.append('data', data);
            }
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            // Fazer a requisição com os filtros
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok') {
                        atualizarTabela(data.data);
                    } else {
                        console.error('Erro ao filtrar projetos:', data.mensagem);
                        alert('Erro ao filtrar projetos: ' + data.mensagem);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao filtrar projetos. Por favor, tente novamente.');
                });
        });
        
        clearButton.addEventListener('click', function() {
            document.getElementById('status').value = '';
            document.getElementById('data').value = '';
            carregarProjetos();
        });
    }
});

function formatarTipoHAE(tipo) {
    if (!tipo) return '';
    return tipo.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

