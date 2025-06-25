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

// Buscar todos os projetos/inscrições dos professores
$sql = "SELECT i.id, i.id_professor, i.id_edital, p.nome AS nome_professor, i.tipo_hae, i.status,
        (SELECT COUNT(*) FROM relatorios_hae r WHERE r.id_inscricao = i.id) AS relatorio_enviado
        FROM inscricoes_hae i
        JOIN professor p ON i.id_professor = p.id
        WHERE i.id_professor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();

$inscricoes = [];
while ($row = $result->fetch_assoc()) {
    $inscricoes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acompanhamento de Inscrições</title>
  <link href="../Assets/style.css" rel="stylesheet">
  <link href="../Assets/acompanhamento.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
  <script src="../Assets/acompanhamento.js"></script>
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
    <h1 class="section-title">Acompanhamento</h1>
    
    <form class="filters" id="filterForm">
    <div class="filter">
  <label for="status">Status</label>
  <select name="status" id="status" required>
    <option value="">Selecione...</option>
    <option value="Pendente">Pendente</option>
    <option value="Aprovado">Aprovado</option>
    <option value="Rejeitado">Rejeitado</option>
  </select>
</div>
      <div class="filter">
        <label for="tip">Tipo HAE</label>
        <select name="tipo" id="tip" required>
          <option value="">Selecione...</option>
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
        <input type="text" id="numero" placeholder="Digite o número de inscrição" pattern="[0-9]*">
      </div>
      <div class="filter-actions">
        <button type="submit" class="btn-primary">Filtrar</button>
        <button type="reset" class="btn-secondary">Limpar</button>
      </div>
    </form>

    <!-- Tabela de Resultados -->
    <div class="results-container">
        <table id="resultsTable" class="results-table">
            <thead>
                <tr>
                    <th>Nº Inscrição</th>
                    <th>Professor</th>
                    <th>Tipo HAE</th>
                    <th>Unidade</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- As inscrições serão carregadas aqui via JavaScript -->
                <?php foreach ($inscricoes as $inscricao): ?>
                <tr>
                    <td><?php echo $inscricao['id']; ?></td>
                    <td><?php echo htmlspecialchars($inscricao['nome_professor']); ?></td>
                    <td><?php echo htmlspecialchars($inscricao['tipo_hae']); ?></td>
                    <td><?php echo htmlspecialchars($inscricao['unidade']); ?></td>
                    <td><?php echo htmlspecialchars($inscricao['status']); ?></td>
                    <td>
                      <?php if ($inscricao['relatorio_enviado'] == 0): ?>
                        <button class="btn-action" onclick="abrirRelatorioModal(<?php echo $inscricao['id']; ?>)">Enviar Relatório Final</button>
                      <?php else: ?>
                        <span>Relatório enviado</span>
                      <?php endif; ?>
                    
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  <!-- Modal de Resumo do Projeto -->
    <div id="projectSummaryModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes da Inscrição</h2>
                <button class="close-modal" onclick="closeProjectSummary()">&times;</button>
            </div>
            <div id="projectSummaryContent"></div>
            <div class="modal-actions">
                <button class="btn-secondary" onclick="printProjectSummary()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button class="btn-primary" onclick="downloadProjectSummary()">
                    <i class="fas fa-download"></i> Baixar PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Relatório Final -->
<div id="relatorioModal" class="modal" style="display:none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Enviar Relatório Final</h2>
      <button class="close-modal" onclick="fecharRelatorioModal()">&times;</button>
    </div>
    <div class="modal-body">
      <form id="relatorioForm" enctype="multipart/form-data">
        <label for="descricaoRelatorio">Descrição do Projeto:</label>
        <textarea id="descricaoRelatorio" name="descricaoRelatorio" required rows="5"></textarea>
        <input type="hidden" id="relatorioIdInscricao" name="id_inscricao" value="">
        <label for="anexoRelatorio">Anexo (opcional):</label>
        <input type="file" id="anexoRelatorio" name="anexoRelatorio" accept=".pdf,.doc,.docx,.jpg,.png">
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="enviarRelatorioFinal()">Enviar</button>
  
    </div>
  </div>
</div>

<!-- Modal de Sucesso ao Enviar Relatório -->
<div id="modalRelatorioEnviado" class="modal" style="display:none;">
    <div class="modal-content confirmation-content">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Relatório enviado com sucesso!</h2>
        <p>Seu relatório foi enviado e está aguardando análise.</p>
        <button class="btn-primary" onclick="fecharModalRelatorioEnviado()">OK</button>
    </div>
</div>

    <div class="pagination" role="navigation" aria-label="Paginação">
      <button aria-label="Primeira página">«</button>
      <button class="active" aria-current="page">1</button>
      <button aria-label="Última página">»</button>
    </div>
  </main>

  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
