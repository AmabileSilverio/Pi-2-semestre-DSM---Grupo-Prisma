// Função para carregar os dados do servidor
async function carregarDados(params = null) {
    try {
        console.log('Iniciando carregamento de dados...');
        let url = '../Controller/buscar_estatisticas.php';
        if (params) url += '?' + params.toString();
        const response = await fetch(url)
        
        if (!response.ok) {
            const errorData = await response.json();
            console.error('Erro na resposta:', errorData);
            throw new Error(errorData.erro || `Erro HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Dados recebidos:', data);

        if (data.erro) {
            throw new Error(data.erro);
        }

        // Atualizar totais
        document.getElementById('total-projetos').textContent = data.total.total || 0;
        document.getElementById('total-aprovados').textContent = data.total.aprovados || 0;
        document.getElementById('total-rejeitados').textContent = data.total.rejeitados || 0;

        // Preparar dados para o gráfico de cursos
        const hoursByCourseData = {
            labels: data.cursos.map(curso => curso.curso),
            datasets: [{
                label: 'Horas Aprovadas',
                data: data.cursos.map(curso => curso.total_horas || 0),
                backgroundColor: '#9c0000',
                borderRadius: 5
            }]
        };

        // Preparar dados para o gráfico de tipo de HAE
        const hoursByTypeHAEData = {
            labels: data.tipo_hae.map(tipo => {
                const tipos = {
                    'estagio_supervisionado': 'Estágio Supervisionado',
                    'trabalho_graduacao': 'Trabalho de Graduação',
                    'iniciacao_cientifica': 'Iniciação Científica',
                    'divulgacao_cursos': 'Divulgação dos Cursos',
                    'administracao_academica': 'Administração Acadêmica',
                    'enade': 'Preparação para ENADE'
                };
                return tipos[tipo.tipo_hae] || tipo.tipo_hae;
            }),
            datasets: [{
                label: 'Horas Aprovadas',
                data: data.tipo_hae.map(tipo => tipo.total_horas || 0),
                backgroundColor: '#9c0000',
                borderRadius: 5
            }]
        };

        // Configuração dos gráficos
        const chartConfig = {
            type: 'bar',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
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
        };

        // Destruir gráficos existentes se houver
        if (window.courseChart) {
            window.courseChart.destroy();
        }
        if (window.typeHAEChart) {
            window.typeHAEChart.destroy();
        }

        // Criar os gráficos
        window.courseChart = new Chart(
            document.getElementById('hoursByCourseChart'),
            {
                ...chartConfig,
                data: hoursByCourseData
            }
        );

        window.typeHAEChart = new Chart(
            document.getElementById('hoursByProfessorChart'),
            {
                ...chartConfig,
                data: hoursByTypeHAEData
            }
        );

    } catch (error) {
        console.error('Erro ao carregar dados:', error);
        alert(`Erro ao carregar dados: ${error.message}`);
    }
}

// Carregar dados quando a página carregar
window.onload = carregarDados;

document.getElementById('filterForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const curso = document.getElementById('filtro-curso').value;
    const tipo = document.getElementById('tip').value;
    const numero = document.getElementById('numero').value;

    const params = new URLSearchParams();
    if (curso) params.append('curso', curso);
    if (tipo) params.append('tipo_hae', tipo);
    if (numero) params.append('numero', numero);

    await carregarDados(params);
});
