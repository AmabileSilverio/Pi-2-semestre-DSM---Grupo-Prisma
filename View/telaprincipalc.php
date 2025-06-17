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
      <a href="telaprincipalc.php">Home</a>
      <a href="listainscritos.php">Inscrições</a>
      <a href="painelestatistico.php">Acompanhamento</a>
      <a href="relatorios.php" class="active">Relatórios</a>
    </div>
  </nav>

<main class="container">
    <h1 class="section-title">Bem vindo, <?php echo htmlspecialchars($nome); ?>!</h1>
    
    <div class="cards-container">
      <a href="listainscritos.php" class="card">
        <img src="../Assets/1.png" alt="Ícone de inscrição">
        <div class="card-title">Inscrições</div>
        <div class="card-description">Analisar os inscritos</div>
      </a>
      <a href="painelestatistico.php" class="card">
        <img src="../Assets/2.png" alt="Ícone de acompanhamento">
        <div class="card-title">Acompanhamento</div>
        <div class="card-description">Acompanhe os projetos de HAE</div>
      </a>
      <a href="relatorios.php" class="card">
        <img src="../Assets/2.png" alt="Ícone de relatórios">
        <div class="card-title">Relatórios</div>
        <div class="card-description">Análise e aprove os relatórios</div>
      </a>
    </div>
  </main>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>

    </div>
  </footer>
</body>
</html>