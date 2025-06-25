<?php
session_start();
include(__DIR__ . '/../conexao.php');
$id_professor = $_SESSION['id'] ?? null;
$nome = 'Usuário';

if ($id_professor) {
    $sql_nome = "SELECT nome FROM professor WHERE id = ?";
    $stmt_nome = $conn->prepare($sql_nome);
    $stmt_nome->bind_param("i", $id_professor);
    $stmt_nome->execute();
    $result_nome = $stmt_nome->get_result();
    $professor = $result_nome->fetch_assoc();
    $nome = $professor['nome'] ?? 'Usuário';
}

$sql = "SELECT r.id, r.id_inscricao, r.descricao, r.anexo, r.status, r.parecer_coordenador, r.data_envio, r.data_analise
        FROM relatorios_hae r
        JOIN inscricoes_hae i ON r.id_inscricao = i.id
        WHERE i.id_professor = ?
        ORDER BY r.data_envio DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();
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
     <a href="telaprincipal.php">Início</a>
      <a href="telaedital.php">Edital</a>
      <a href="telaacompanhamento.php">Inscrições</a>
      <a href="relatorios_professor.php">Relatórios</a>
      <a href="telaagenda.php">Agenda</a>
    </div>
    </div>
  </nav>

  <main class="container">
    <h1 class="section-title">Relatórios Finais dos Professores</h1>
    <div class="results-table">
      <table>
        <thead>
          <tr>
            <th>Nº Inscrição</th>
            <th>Descrição</th>
            <th>Anexo</th>
            <th>Status</th>
            <th>Parecer do Coordenador</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($relatorio = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($relatorio['id_inscricao']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($relatorio['descricao'])); ?></td>
              <td>
                <?php if ($relatorio['anexo']): ?>
                  <a href="/Pi2/uploads_relatorios/<?php echo rawurlencode($relatorio['anexo']); ?>" target="_blank">Baixar</a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
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
                <?php echo nl2br(htmlspecialchars($relatorio['parecer_coordenador'])); ?>
              </td>
              <td>
                <?php if ($relatorio['status'] === 'Correção Solicitada'): ?>
                  <button class="btn-action" onclick="abrirModalReenvio(<?php echo $relatorio['id']; ?>)">Reenviar</button>
                <?php else: ?>
                  <span>-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
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
          <label for="parecerCoordenadorView">Parecer do Coordenador:</label>
          <textarea id="parecerCoordenadorView" name="parecerCoordenadorView" required rows="4"></textarea>
          <input type="hidden" id="relatorioIdAnalise" name="relatorio_id" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-primary" onclick="aprovarRelatorio()">Aprovar</button>
        <button class="btn-danger" onclick="solicitarCorrecaoRelatorio()">Solicitar Correção</button>
      </div>
    </div>
  </div>

  <!-- Modal de Reenvio de Relatório -->
  <div id="modalReenvio" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Reenviar Relatório Corrigido</h2>
        <button class="close-modal" onclick="fecharModalReenvio()">&times;</button>
      </div>
      <div class="modal-body">
        <form id="formReenvio" enctype="multipart/form-data">
          <label for="descricaoReenvio">Nova Descrição:</label>
          <textarea id="descricaoReenvio" name="descricaoReenvio" required rows="5"></textarea>
          <label for="anexoReenvio">Novo Anexo (opcional):</label>
          <input type="file" id="anexoReenvio" name="anexoReenvio" accept=".pdf,.doc,.docx,.jpg,.png">
          <input type="hidden" id="idRelatorioReenvio" name="id_relatorio" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-primary" onclick="enviarReenvioRelatorio()">Enviar</button>
        <button type="button" class="btn-danger" onclick="fecharModalReenvio()">Cancelar</button>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmação de Envio de Relatório -->
  <div id="modalConfirmacaoRelatorio" class="modal">
    <div class="modal-content confirmation-content">
      <div class="confirmation-icon">
        <i class="fas fa-check-circle" style="font-size:48px;color:#28a745;"></i>
      </div>
      <h2>Relatório enviado!</h2>
      <p>Seu relatório final foi enviado com sucesso.</p>
      <button class="btn-primary" onclick="fecharModalConfirmacaoRelatorio()">OK</button>
    </div>
  </div>

  <!-- Modal de Confirmação de Reenvio de Relatório -->
  <div id="modalRelatorioReenviado" class="modal">
    <div class="modal-content confirmation-content">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Relatório reenviado com sucesso!</h2>
        <button class="btn-primary" onclick="fecharModalRelatorioReenviado()">OK</button>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
    </div>
  </footer>
   <script src="../Assets/acompanhamento.js"></script>

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
