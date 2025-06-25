<?php
session_start();
include(__DIR__ . '/../conexao.php');
// Exemplo: buscar dados do coordenador com id guardado na sessão
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
  <title>Home - Fatec Itapira</title>
  <link href="../Assets/stylecoordenador.css" rel="stylesheet">
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
      <a href="telaprincipalc.php">Início</a>
     <a href="telaeditalcoordenador.php">Editais</a>
      <a href="listainscritos.php">Inscrições</a>
      <a href="painelestatistico.php">Acompanhamento</a>
      <a href="relatorios.php" class="active">Relatórios</a>
    </div>
  </nav>

<main class="container">
    <h1 class="section-title">Bem vindo, <?php echo htmlspecialchars($nome); ?>!</h1>
    
    <div class="cards-container">
      <a href="cadastraredital.php" class="card">
        <img src="../Assets/1.png" alt="Ícone de inscrição">
        <div class="card-title">Cadastrar</div>
        <div class="card-description">Cadastrar edital</div>
      </a>
      <a href="listaeditalcoordenador.php" class="card">
        <img src="../Assets/1.png" alt="Ícone de inscrição">
        <div class="card-title">Lista de editais</div>
        <div class="card-description">Analise os editais em aberto</div>
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
