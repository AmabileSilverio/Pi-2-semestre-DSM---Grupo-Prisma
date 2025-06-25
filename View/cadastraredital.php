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
      <a href="telaprincipalc.php">Início</a>
      <a href="telaeditalcoordenador.php">Editais</a>
      <a href="listainscritos.php">Inscrições</a>
      <a href="painelestatistico.php">Acompanhamento</a>
      <a href="relatorios.php" class="active">Relatórios</a>
    </div>
  </nav>
  
<main class="container">
    <h1 class="section-title">Cadastrar Edital</h1>
    <form id="editalForm" action="salvar_edital.php" method="post" style="max-width:500px;margin:auto;">
    <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
    </div>

    <div class="form-group">
        <label for="unidade">Unidade:</label>
        <select id="unidade" name="unidade" required>
            <option value="">Selecione</option>
            <option value="Americana">Americana</option>
            <option value="Araras">Araras</option>
            <option value="Campinas">Campinas</option>
            <option value="Itapira">Itapira</option>
            <option value="Mogi Mirim">Mogi Mirim</option>
            <option value="Santo André">Santo André</option>
        </select>
    </div>

    <div class="form-group">
        <label for="data_inicio">Data de Início:</label>
        <input type="date" id="data_inicio" name="data_inicio" required>
    </div>

    <div class="form-group">
        <label for="data_termino">Data de Término:</label>
        <input type="date" id="data_termino" name="data_termino" required>
    </div>

    <div class="form-group">
        <label for="semestre">Semestre:</label>
        <select id="semestre" name="semestre" required>
            <option value="">Selecione</option>
            <option value="1º semestre">1º semestre</option>
            <option value="2º semestre">2º semestre</option>
        </select>
    </div>
    <button type="submit" class="btn-primary">Cadastrar</button>
</form>
    <?php if (isset($_GET['sucesso'])): ?>
  <div class="alert alert-success">Edital cadastrado com sucesso!</div>
<?php endif; ?>
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
<script src="../Assets/editais.js"></script>

<div id="modalSucessoEdital" class="modal" style="display:none;">
  <div class="modal-content confirmation-content">
    <div class="confirmation-icon">
      <i class="fas fa-check-circle" style="font-size:48px;color:#28a745;"></i>
    </div>
    <h2>Edital cadastrado!</h2>
    <p>O edital foi cadastrado com sucesso.</p>
    <button class="btn-primary" onclick="fecharModalSucessoEdital()">OK</button>
  </div>
</div>
</body>
</html>
