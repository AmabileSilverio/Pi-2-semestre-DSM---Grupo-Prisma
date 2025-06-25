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

// Buscar todos os editais cadastrados
$sql = "SELECT * FROM editais_hae ORDER BY data_criacao DESC";
$result = $conn->query($sql);

$editais = [];
while ($row = $result->fetch_assoc()) {
    $editais[] = $row;
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
      <a href="telaprincipal.php">Início</a>
      <a href="telaedital.php">Edital</a>
      <a href="telaacompanhamento.php">Inscrições</a>
      <a href="relatorios_professor.php">Relatórios</a>
      <a href="telaagenda.php">Agenda</a>
    </div>
  </nav>

  <div class="container">
    <h2 class="section-title">Editais Disponíveis</h2>
<table>
  <thead>
    <tr>
      <th>Título</th>
      <th>Unidade</th>
      <th>Data de Início</th>
      <th>Data de Término</th>
      <th>Semestre</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($editais)): ?>
      <tr><td colspan="5">Nenhum edital disponível.</td></tr>
    <?php else: ?>
      <?php foreach ($editais as $edital): ?>
        <tr>
          <td><?php echo htmlspecialchars($edital['titulo']); ?></td>
          <td><?php echo htmlspecialchars($edital['unidade']); ?></td>
          <td><?php echo date('d/m/Y', strtotime($edital['data_inicio'])); ?></td>
          <td><?php echo date('d/m/Y', strtotime($edital['data_termino'])); ?></td>
          <td><?php echo htmlspecialchars($edital['semestre']); ?></td>
          <td>
            <a href="formulario.php?id_edital=<?php echo $edital['id']; ?>" class="btn-primary" style="padding:6px 16px; text-decoration:none;">
              Inscrever-se
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
    <div class="pagination">
      <button>«</button>
      <button class="active">1</button>
      <button>»</button>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
  </footer>

  <div vw class="enabled">
  <div vw-access-button class="active"></div>
  <div vw-plugin-wrapper>
    <div class="vw-plugin-top-wrapper"></div>
  </div>
</div>

<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
  new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>


</body>
</html>
