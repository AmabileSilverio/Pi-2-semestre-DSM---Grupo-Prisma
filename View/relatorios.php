<?php

session_start();
include(__DIR__ . '/../conexao.php');

// Buscar nome do coordenador
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

// Buscar todos os relatórios
$sql = "SELECT r.id, r.id_inscricao, r.status, i.nome AS nome_professor, i.tipo_hae, r.descricao, r.anexo
        FROM relatorios_hae r
        JOIN inscricoes_hae i ON r.id_inscricao = i.id
        ORDER BY r.data_envio DESC";
$result = $conn->query($sql);

$relatorios = [];
while ($row = $result->fetch_assoc()) {
    $relatorios[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatórios Finais - Fatec Itapira</title>
  <link href="../Assets/stylecoordenador.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <!-- Área Superior -->
  <div class="top-area">
    <div class="logo-container">
      <img src="../Assets/logo-fatec_itapira (2).png" alt="Logo Fatec Itapira">
    </div>
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
    <h1 class="section-title">Relatórios Finais dos Professores</h1>
    <div class="results-table">
      <table>
        <thead>
          <tr>
            <th>Nº Inscrição</th>
            <th>Professor</th>
            <th>Tipo HAE</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($relatorios as $relatorio): ?>
  <tr>
    <td><?php echo htmlspecialchars($relatorio['id_inscricao']); ?></td>
    <td><?php echo htmlspecialchars($relatorio['nome_professor']); ?></td>
    <td><?php echo formatarTipoHAE($relatorio['tipo_hae']); ?></td>
    <td>
      <?php
        // Gera a classe CSS baseada no status
        $status_class = strtolower(str_replace([' ', 'ç', 'ã', 'ó'], ['', 'c', 'a', 'o'], $relatorio['status']));
      ?>
      <span class="status-badge <?php echo $status_class; ?>">
        <?php echo htmlspecialchars($relatorio['status']); ?>
      </span>
    </td>
   <td>
  <button class="btn-action" onclick="abrirModalVerRelatorio(
    '<?php echo htmlspecialchars(addslashes($relatorio['descricao'])); ?>',
    '<?php echo $relatorio['anexo'] ? '../uploads_relatorios/' . rawurlencode($relatorio['anexo']) : ''; ?>',
    <?php echo $relatorio['id']; ?>
  )">
    <i class="fas fa-eye"></i> Ver Relatório
  </button>
</td>
  </tr>
<?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal de Análise de Relatório -->
  <div id="analiseRelatorioModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Análise do Relatório Final</h2>
        <button class="close-modal" onclick="fecharAnaliseRelatorioModal()">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Descrição do Professor:</strong></p>
        <p id="descricaoRelatorioProfessor"></p>
        <p><strong>Anexo:</strong> <a id="anexoRelatorioLink" href="#" target="_blank">Baixar</a></p>
        <form id="parecerForm">
          <label for="parecerCoordenador">Parecer do Coordenador:</label>
          <textarea id="parecerCoordenador" name="parecerCoordenador" required rows="4"></textarea>
          <input type="hidden" id="relatorioIdAnalise" name="relatorio_id" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-primary" onclick="aprovarRelatorio()">Aprovar</button>
        <button class="btn-danger" onclick="solicitarCorrecaoRelatorio()">Solicitar Correção</button>
      </div>
    </div>
  </div>

  <!-- Modal de Visualização de Relatório -->
  <div id="verRelatorioModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Relatório Final</h2>
        <button class="close-modal" onclick="fecharModalVerRelatorio()">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Descrição:</strong></p>
        <p id="descricaoRelatorioView"></p>
        <p><strong>Anexo:</strong> <a id="anexoRelatorioView" href="#" target="_blank">Baixar</a></p>
        <form id="parecerFormView">
          <label for="parecerCoordenadorView">Parecer do Coordenador:</label>
          <textarea id="parecerCoordenadorView" name="parecerCoordenadorView" required rows="4"></textarea>
          <input type="hidden" id="relatorioIdView" name="relatorio_id" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-primary" onclick="aprovarRelatorioView()">Aprovar</button>
        <button class="btn-danger" onclick="solicitarCorrecaoRelatorioView()">Solicitar Correção</button>
      </div>
    </div>
  </div>

  <!-- Modal de Relatório Aprovado pelo Coordenador -->
<div id="modalRelatorioAprovado" class="modal">
    <div class="modal-content confirmation-content">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Relatório aprovado com sucesso!</h2>
        <button class="btn-primary" onclick="fecharModalRelatorioAprovado()">OK</button>
    </div>
</div>

<!-- Modal de Correção Solicitada -->
<div id="modalCorrecaoSolicitada" class="modal">
    <div class="modal-content confirmation-content">
        <div class="confirmation-icon">
            <i class="fas fa-exclamation-circle" style="color:#ff9800;"></i>
        </div>
        <h2>Correção solicitada!</h2>
        <p>O relatório foi devolvido para correção do professor.</p>
        <button class="btn-primary" onclick="fecharModalCorrecaoSolicitada()">OK</button>
    </div>
</div>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
    </div>
  </footer>
   <script src="../Assets/relatorio.js"></script>

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

<?php
function formatarTipoHAE($tipo) {
    return ucwords(str_replace('_', ' ', $tipo));
}
?>

</body>
</html>
