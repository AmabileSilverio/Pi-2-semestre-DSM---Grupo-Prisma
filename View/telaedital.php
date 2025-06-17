<?php
session_start();
include(__DIR__ . '/../conexao.php');
// Buscar dados do professor com id guardado na sessão
$id_professor = $_SESSION['id'] ?? null;

if ($id_professor) {
    $sql = "SELECT nome FROM professor WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_professor);
    $stmt->execute();
    $result = $stmt->get_result();
    $professor = $result->fetch_assoc();
    $nome = $professor['nome'] ?? 'Usuário';
} else {
    $nome = 'Usuário';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edital - Fatec Itapira</title>
  <link href="../Assets/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
      <a href="telaprincipal.php" aria-current="page">Home</a>
      <a href="formulario.php">Inscrições</a>
      <a href="telaacompanhamento.php">Acompanhamento</a>
      <a href="relatorios.php" class="active">Relatórios</a>
      <a href="telaedital.php">Edital</a>
      <a href="telaagenda.php">Agenda</a>
    </div>
  </nav>

  <div class="container">
    <div class="section-title">Edital</div>
    <div class="filters">
      <div class="filter">
        <label for="unid">Unidade:</label>
        <select name="unidade" id="unid">
          <option value="disabled selected">Selecione...</option>
          <option value="unidade_americana">Americana</option>
          <option value="unidade_araras">Araras</option>
          <option value="unidade_campinas">Campinas</option>
          <option value="unidade_itapira">Itapira</option>
          <option value="unidade_mogi_mirim">Mogi Mirim</option>
          <option value="unidade_santo_andre">Santo André</option>
        </select>
      </div>
      <div class="filter">
        <label for="tip">Tipo HAE</label>
        <select name="tipo" id="tip">
          <option value="disabled selected">Selecione...</option>
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
        <input type="text" id="numero" placeholder="Digite o número de inscrição">
      </div>
      <div class="filter">
        <label for="numero">Ano</label>
        <input type="text" id="numero" placeholder="Digite o número de inscrição">
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>Unidade</th>
          <th>Nº inscrição</th>
          <th>Tipo HAE</th>
          <th>Professor</th>
          <th>Número da Matrícula</th>
          <th>Situação</th>
          <th>Ano</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <div class="pagination">
      <button>«</button>
      <button class="active">1</button>
      <button>2</button>
      <button>3</button>
      <button>4</button>
      <button>5</button>
      <button>...</button>
      <button>50</button>
      <button>»</button>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
  </footer>
</body>
</html>