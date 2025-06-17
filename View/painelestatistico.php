<?php
session_start();
include(__DIR__ . '/../conexao.php');
$id_coordenador = $_SESSION['id'] ?? null;

if ($id_coordenador) {
    $sql = "SELECT nome FROM coordenador WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_coordenador);
    $stmt->execute();
    $result = $stmt->get_result();
    $coordenador = $result->fetch_assoc();
    $nome = $coordenador['nome'] ?? 'Usuário';
} else {
    $nome = 'Usuário';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Estatístico - Fatec Itapira</title>
  <link href="../Assets/stylecoordenador.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <!-- Área Superior -->
  <div class="top-area">
    <!-- Barra com o logo da Fatec e CPS -->
    <div class="logo-container">
      <img src="../Assets/logo-fatec_itapira (2).png" alt="Logo Fatec Itapira">
    </div>

 <!-- Perfil do Usuário -->
    <div class="user-profile">
  <div class="user-avatar">
    <?php echo strtoupper(substr($nome, 0, 1)); ?>
  </div>
  <span><?php echo htmlspecialchars($nome); ?></span>
  <a href="index.php">
    <i class="fas fa-sign-out-alt"></i>
  </a>
</div>
  </div>

  <!-- Barra de Navegação e Busca -->
  <nav class="navbar" role="navigation" aria-label="Menu principal">
    <div class="nav-links">
        <a href="telaprincipalc.php">Home</a>
        <a href="listainscritos.php">Inscrições</a>
        <a href="painelestatistico.php">Acompanhamento</a>
        <a href="relatorios.php" class="active">Relatórios</a>
      </div>
  </nav>
  <main class="container">
    <h1 class="section-title">Acompanhamento</h1>
    <div class="filtro-container">
    
    <form class="filters" id="filterForm">
      <div class="filter">
        <label for="filtro-curso">Curso:</label>
        <select id="filtro-curso" onchange="filtrarPorCurso()">
          <option value="">Selecione...</option>
          <option value="DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA">Desenvolvimento de Software Multiplataforma</option>
          <option value="GESTAO EMPRESARIAL">Gestão Empresarial</option>
          <option value="GESTÃO DA PRODUÇÃO INDUSTRIAL">Gestão da Produção Industrial</option>
        </select>
      </div>
      <div class="filter">
        <label for="tip">Tipo HAE</label>
        <select name="tipo" id="tip" required>
          <option value="">Selecione...</option>
          <option value="estagio_supervisionado">Estágio Supervisionado</option>
          <option value="trabalho_graduacao">Trabalho de Graduação</option>
          <option value="iniciacao_cientifica">Iniciação Científica</option>
          <option value="divulgacao_cursos">Divulgação dos Cursos</option>
          <option value="administracao_academica">Administração Acadêmica</option>
          <option value="enade">Preparação para ENADE</option>
        </select>
      </div>
      <div class="filter">
        <label for="numero">Número de inscrição</label>
        <input type="text" id="numero" placeholder="Digite o número de inscrição" pattern="[0-9]*">
      </div>
      <div class="filter-actions">
        <button type="submit" class="btn-primary">Filtrar</button>
        <button type="reset" class="btn-secondary">Limpar</button>
      </div>
    </form>

    <!-- Cards de Estatísticas -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
          <h3>Total de Projetos</h3>
          <p class="stat-number" id="total-projetos">0</p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon approved">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
          <h3>Projetos Aprovados</h3>
          <p class="stat-number" id="total-aprovados">0</p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon rejected">
          <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-info">
          <h3>Projetos Rejeitados</h3>
          <p class="stat-number" id="total-rejeitados">0</p>
        </div>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="stats-row">
      <div class="stat-card wide">
        <h3>Horas Aprovadas por Curso</h3>
        <div class="chart-container">
          <canvas id="hoursByCourseChart"></canvas>
        </div>
      </div>
      <div class="stat-card wide">
        <h3>Horas Aprovadas por Tipo de HAE</h3>
        <div class="chart-container">
          <canvas id="hoursByProfessorChart"></canvas>
        </div>
      </div>
    </div>
  </main>
  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
    </div>
  </footer>
  <script src="../Assets/painelestatistico.js"></script>
</body>
</html> 