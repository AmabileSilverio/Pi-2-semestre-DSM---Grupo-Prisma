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

// Buscar todos os projetos/inscrições dos professores
$sql = "SELECT i.id, i.id_professor, p.nome AS  nome_professor, i.tipo_hae, i.status, i.anexo
        FROM inscricoes_hae i
        JOIN professor p ON i.id_professor = p.id";
$result = $conn->query($sql);

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
  <title>Home - Fatec Itapira</title>
  <link href="../Assets/stylecoordenador.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="../Assets/listainscritos.js"></script>
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
    <h1 class="section-title">Inscrições</h1>
    
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
<?php foreach ($inscricoes as $inscricao): ?>
  <tr>
    <td><?php echo htmlspecialchars($inscricao['id']); ?></td>
    <td><?php echo htmlspecialchars($inscricao['nome_professor']); ?></td>
    <td><?php echo htmlspecialchars($inscricao['tipo_hae']); ?></td>
    <td><span class="status-badge"><?php echo htmlspecialchars($inscricao['status']); ?></span></td>
    <td>
      <!-- Botões de ação aqui -->
      <?php if (!empty($inscricao['anexo'])): ?>
        <a href="../uploads_inscricoes/<?php echo urlencode($inscricao['anexo']); ?>" target="_blank">Baixar arquivo</a>
      <?php else: ?>
        Nenhum arquivo enviado.
      <?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Modal do Resumo do Projeto -->
    <div id="projectSummaryModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Detalhes da Inscrição</h2>
          <button class="close-modal" onclick="closeProjectSummary()">&times;</button>
        </div>
        <div class="modal-body">
          <div id="projectSummaryContent">
            <!-- O conteúdo será preenchido via JavaScript -->
            <div class="horarios-section">
              <h3>Horários do Projeto</h3>
              <div class="horarios-list">
                <!-- Os horários serão preenchidos via JavaScript -->
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-secondary" onclick="printProjectSummary()">
            <i class="fas fa-print"></i> Imprimir
          </button>
          <button class="btn-primary" onclick="downloadProjectSummary()">
            <i class="fas fa-download"></i> Baixar PDF
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Aprovação do Projeto -->
    <div id="approveProjectModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Aprovar Projeto</h2>
          <button class="close-modal" onclick="closeApproveModal()">&times;</button>
        </div>
        <div class="modal-body">
          <div class="project-summary">
            <h3>Resumo do Projeto</h3>
            <div class="project-info">
              <p><strong>Título:</strong> <span id="approveProjectTitle"></span></p>
              <p><strong>Professor:</strong> <span id="approveProjectProfessor"></span></p>
              <p><strong>Unidade:</strong> <span id="approveProjectUnit"></span></p>
              <p><strong>Horas Solicitadas:</strong> <span id="approveProjectHours"></span></p>
            </div>
          </div>
          
          <form id="approveProjectForm" class="approve-form">
            <div class="form-group">
              <label for="approvedHours">Horas Aprovadas:</label>
              <input type="time" id="approvedHours" name="approvedHours" required 
                     min="00:00" max="40:00" step="1800">
            </div>
            
            <div class="form-group">
              <label for="startDate">Data de Início:</label>
              <input type="date" id="startDate" name="startDate" required>
            </div>
            
            <div class="form-group">
              <label for="endDate">Data de Término:</label>
              <input type="date" id="endDate" name="endDate" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn-secondary" onclick="closeApproveModal()">Cancelar</button>
          <button class="btn-primary" onclick="approveProject()">
            <i class="fas fa-check"></i> Confirmar Aprovação
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmationModal" class="modal">
      <div class="modal-content confirmation-content">
        <div class="confirmation-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h2>Projeto Aprovado!</h2>
        <p>O projeto foi aprovado com sucesso.</p>
        <button class="btn-primary" onclick="closeConfirmationModal()">OK</button>
      </div>
    </div>

    <!-- Modal de Rejeição do Projeto -->
    <div id="rejectProjectModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Rejeitar Projeto</h2>
          <button class="close-modal" onclick="closeRejectModal()">&times;</button>
        </div>
        <div class="modal-body">
          <div class="project-summary">
            <h3>Resumo do Projeto</h3>
            <div class="project-info">
              <p><strong>Título:</strong> <span id="rejectProjectTitle"></span></p>
              <p><strong>Professor:</strong> <span id="rejectProjectProfessor"></span></p>
              <p><strong>Unidade:</strong> <span id="rejectProjectUnit"></span></p>
              <p><strong>Horas Solicitadas:</strong> <span id="rejectProjectHours"></span></p> <!-- NOVA LINHA -->
            </div>
          </div>
          
          <form id="rejectProjectForm" class="reject-form">
            <div class="form-group">
              <label for="rejectReason">Motivo da Rejeição:</label>
              <textarea id="rejectReason" name="rejectReason" required 
                        rows="4" placeholder="Digite o motivo da rejeição..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn-secondary" onclick="closeRejectModal()">Cancelar</button>
          <button class="btn-primary reject" onclick="rejectProject()">
            <i class="fas fa-times"></i> Confirmar Rejeição
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmação de Rejeição -->
    <div id="rejectConfirmationModal" class="modal">
      <div class="modal-content confirmation-content">
        <div class="confirmation-icon reject">
          <i class="fas fa-times-circle"></i>
        </div>
        <h2>Projeto Recusado</h2>
        <p>O projeto foi recusado com sucesso.</p>
        <button class="btn-primary" onclick="closeRejectConfirmationModal()">OK</button>
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
</body>
</html>