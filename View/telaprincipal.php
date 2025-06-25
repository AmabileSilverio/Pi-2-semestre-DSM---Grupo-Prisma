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
  <title>Home - Fatec Itapira</title>
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
<main class="container">
    <h1 class="section-title">Bem vindo, <?php echo htmlspecialchars($nome); ?>!</h1>
    
    <div class="cards-container">
       <a href="telaedital.php" class="card">
        <img src="../Assets/4.png" alt="Ícone de edital">
        <div class="card-title">Edital</div>
        <div class="card-description">Acompanhe a lista de editais em aberto</div>
      </a>
      <a href="telaacompanhamento.php" class="card">
        <img src="../Assets/acomp.png" alt="Ícone de acompanhamento">
        <div class="card-title">Acompanhamento</div>
        <div class="card-description">Acompanhe o progresso da sua HAE</div>
      </a>

      <a href="relatorios_professor.php" class="card">
        <img src="../Assets/2.png" alt="Ícone de acompanhamento">
        <div class="card-title">Relatórios</div>
        <div class="card-description">Acompanhe o progresso dos seus relatórios</div>
      </a>

      <a href="telaagenda.php" class="card">
        <img src="../Assets/5.png" alt="Ícone de agenda">
        <div class="card-title">Agenda</div>
        <div class="card-description">Acompanhe seus processos e prazos</div>
      </a>
    </div>
  </main>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>

    </div>
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
