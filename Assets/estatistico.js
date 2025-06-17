document.addEventListener('DOMContentLoaded', function() {
    carregarEstatisticas();
    carregarHorasPorTipoHAE();
});

function carregarEstatisticas(curso = '') {
    const url = curso ? `Controller/buscar_estatisticas.php?curso=${encodeURIComponent(curso)}` : 'Controller/buscar_estatisticas.php';
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                throw new Error(data.erro);
            }
            atualizarEstatisticas(data);
        })
        .catch(erro => {
            console.error('Erro ao carregar estatísticas:', erro);
            alert('Erro ao carregar estatísticas. Por favor, tente novamente.');
        });
}

function carregarHorasPorTipoHAE() {
    fetch('Controller/buscar_horas_tipo_hae.php')
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                throw new Error(data.erro);
            }
            atualizarGraficoHoras(data);
        })
        .catch(erro => {
            console.error('Erro ao carregar horas por tipo de HAE:', erro);
        });
}

function filtrarPorCurso() {
    const curso = document.getElementById('filtro-curso').value;
    carregarEstatisticas(curso);
}

function atualizarEstatisticas(data) {
    // Atualizar totais gerais
    document.getElementById('total-projetos').textContent = data.total.total || 0;
    document.getElementById('total-aprovados').textContent = data.total.aprovados || 0;
    document.getElementById('total-rejeitados').textContent = data.total.rejeitados || 0;
    document.getElementById('total-inscritos').textContent = data.total.inscritos || 0;

    // Atualizar estatísticas por curso
    const tbodyCursos = document.getElementById('estatisticas-cursos');
    tbodyCursos.innerHTML = '';
    
    data.cursos.forEach(curso => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${curso.curso}</td>
            <td>${curso.total}</td>
            <td>${curso.aprovados}</td>
            <td>${curso.rejeitados}</td>
            <td>${curso.inscritos}</td>
        `;
        tbodyCursos.appendChild(tr);
    });

    // Atualizar estatísticas por semestre
    const tbodySemestres = document.getElementById('estatisticas-semestres');
    tbodySemestres.innerHTML = '';
    
    data.semestres.forEach(semestre => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${semestre.semestre}</td>
            <td>${semestre.total}</td>
            <td>${semestre.aprovados}</td>
            <td>${semestre.rejeitados}</td>
            <td>${semestre.inscritos}</td>
        `;
        tbodySemestres.appendChild(tr);
    });
}

function atualizarGraficoHoras(data) {
    const ctx = document.getElementById('horasChart').getContext('2d');
    
    // Destruir gráfico existente se houver
    if (window.horasChart) {
        window.horasChart.destroy();
    }

    // Preparar dados para o gráfico
    const labels = data.map(item => {
        const tipos = {
            'estagio_supervisionado': 'Estágio Supervisionado',
            'trabalho_graduacao': 'Trabalho de Graduação',
            'iniciacao_cientifica': 'Iniciação Científica',
            'divulgacao_cursos': 'Divulgação dos Cursos',
            'administracao_academica': 'Administração Acadêmica',
            'enade': 'Preparação para ENADE'
        };
        return tipos[item.tipo_hae] || item.tipo_hae;
    });
    
    const valores = data.map(item => item.total_horas);

    // Criar novo gráfico
    window.horasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Horas Aprovadas',
                data: valores,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Horas'
                    }
                }
            }
        }
    });
} 