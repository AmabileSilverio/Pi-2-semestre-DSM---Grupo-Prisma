<?php

session_start();
include(__DIR__ . '/../conexao.php');
// Exemplo: buscar dados do coordenador com id guardado na sessão
$id_coordenador = $_SESSION['id'] ?? null;
$nome = 'Coordenador';

if ($id_coordenador) {
    $sql = "SELECT nome FROM coordenador WHERE id = ?";
    $stmt_nome = $conn->prepare($sql);
    $stmt_nome->bind_param("i", $id_coordenador);
    $stmt_nome->execute();
    $result_nome = $stmt_nome->get_result();
    $coordenador = $result_nome->fetch_assoc();
    if ($coordenador) {
        $nome = $coordenador['nome'];
    }
}

// Verifica se o ID do edital foi passado
$id = $_POST['id'] ?? $_GET['id'] ?? null;
if (!$id) {
    echo "Edital não encontrado.";
    exit;
}

// Busca os dados do edital
$stmt = $conn->prepare("SELECT * FROM editais_hae WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$edital = $result->fetch_assoc();

if (!$edital) {
    echo "Edital não encontrado.";
    exit;
}

// Atualiza o edital se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $unidade = $_POST['unidade'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_termino = $_POST['data_termino'] ?? '';
    $semestre = $_POST['semestre'] ?? '';

    $stmt = $conn->prepare("UPDATE editais_hae SET titulo=?, unidade=?, data_inicio=?, data_termino=?, semestre=? WHERE id=?");
    $stmt->bind_param("sssssi", $titulo, $unidade, $data_inicio, $data_termino, $semestre, $id);

    if ($stmt->execute()) {
        header("Location: editar_edital.php?id=$id&sucesso=1");
        exit;
    } else {
        $erro = "Erro ao atualizar edital.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Edital</title>
    <link href="../Assets/stylecoordenador.css" rel="stylesheet">
    <script src="../Assets/editais.js"></script>
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
  
    <div class="container">
        <h2 class="section-title">Editar Edital</h2>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form method="post" id="editalForm" style="max-width:500px;margin:auto;">
            <input type="hidden" name="id" value="<?php echo $edital['id']; ?>">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($edital['titulo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="unidade">Unidade:</label>
                <select id="unidade" name="unidade" required>
                    <option value="">Selecione</option>
                    <option value="Americana" <?php if($edital['unidade']=='Americana') echo 'selected'; ?>>Americana</option>
                    <option value="Araras" <?php if($edital['unidade']=='Araras') echo 'selected'; ?>>Araras</option>
                    <option value="Campinas" <?php if($edital['unidade']=='Campinas') echo 'selected'; ?>>Campinas</option>
                    <option value="Itapira" <?php if($edital['unidade']=='Itapira') echo 'selected'; ?>>Itapira</option>
                    <option value="Mogi Mirim" <?php if($edital['unidade']=='Mogi Mirim') echo 'selected'; ?>>Mogi Mirim</option>
                    <option value="Santo André" <?php if($edital['unidade']=='Santo André') echo 'selected'; ?>>Santo André</option>
                </select>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" id="data_inicio" name="data_inicio" value="<?php echo $edital['data_inicio']; ?>" required>
            </div>
            <div class="form-group">
                <label for="data_termino">Data de Término:</label>
                <input type="date" id="data_termino" name="data_termino" value="<?php echo $edital['data_termino']; ?>" required>
            </div>
            <div class="form-group">
                <label for="semestre">Semestre:</label>
                <select id="semestre" name="semestre" required>
                    <option value="">Selecione</option>
                    <option value="1º semestre" <?php if($edital['semestre']=='1º semestre') echo 'selected'; ?>>1º semestre</option>
                    <option value="2º semestre" <?php if($edital['semestre']=='2º semestre') echo 'selected'; ?>>2º semestre</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Salvar Alterações</button>
        </form>
    </div>
    <div id="modalSucessoEdital" class="modal" style="display:none;">
  <div class="modal-content confirmation-content">
    <div class="confirmation-icon">
      <i class="fas fa-check-circle" style="font-size:48px;color:#28a745;"></i>
    </div>
    <h2>Edital alterado com sucesso!</h2>
    <p>As alterações foram salvas.</p>
    <button class="btn-primary" onclick="fecharModalSucessoEdital()">OK</button>
  </div>
</div>
    <footer class="footer">
        <div class="footer-content">
          <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
          <p>Desenvolvido por Prisma</p>
        </div>
    </footer>
    <?php if (isset($_GET['sucesso'])): ?>
<script>
    window.onload = function() {
        abrirModalSucessoEdital();
    }
</script>
<?php endif; ?>
</body>
</html>
