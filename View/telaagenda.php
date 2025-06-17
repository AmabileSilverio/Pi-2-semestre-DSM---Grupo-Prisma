<?php
session_start();
include(__DIR__ . '/../conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header('Location: index.php');
    exit;
}

// Buscar dados do professor
$id_professor = $_SESSION['id'];
$sql = "SELECT nome FROM professor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();
$professor = $result->fetch_assoc();
$nome = $professor['nome'] ?? 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agenda - Fatec Itapira</title>
  <link href="../Assets/style.css" rel="stylesheet">
  <link href="../Assets/agenda.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="../Assets/agenda.js"></script>
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
      <a href="telaprincipal.php">Home</a>
      <a href="formulario.php">Inscrições</a>
      <a href="telaacompanhamento.php">Acompanhamento</a>
      <a href="relatorios_professor.php" class="active">Relatórios</a>
      <a href="telaedital.php">Edital</a>
      <a href="telaagenda.php">Agenda</a>
    </div>
  </nav>

  <main class="container">
    <h1 class="section-title">Minha Agenda</h1>

    <!-- Filtros -->
    <div class="filters">
      <div class="filter">
        <label for="status">Status:</label>
        <select id="status" required>
          <option value="">Todos</option>
          <option value="pendente">Pendente</option>
          <option value="em_andamento">Em Andamento</option>
          <option value="concluido">Concluído</option>
        </select>
      </div>

      <div class="filter">
        <label for="data">Data:</label>
        <input type="date" id="data">
      </div>

      <div class="filter-actions">
        <button class="btn-primary">Filtrar</button>
        <button class="btn-secondary">Limpar</button>
      </div>
    </div>

    <!-- Tabela de Processos -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Projeto</th>
            <th>Data Início</th>
            <th>Hora Início</th>
            <th>Prazo Final</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <!-- Dados serão carregados via JavaScript -->
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal de Detalhes do Projeto -->
  <div id="projectDetailsModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div id="projectDetailsContent"></div>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
    </div>
  </footer>
</body>
</html> 