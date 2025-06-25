<?php
session_start();
include(__DIR__ . '/../conexao.php');

// Buscar dados do coordenador com id guardado na sessão
$id_coordenador = $_SESSION['id'] ?? null;

if ($id_coordenador) {
    $sql = "SELECT nome FROM coordenador WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_coordenador);
    $stmt->execute();
    $result = $stmt->get_result();
    $coordenador = $result->fetch_assoc();
    $nome = $coordenador['nome'] ?? 'Coordenador';
} else {
    $nome = 'Coordenador';
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
  <title>Editais - Coordenador</title>
  <link href="../Assets/stylecoordenador.css" rel="stylesheet">
  <script src="../Assets/editais.js"></script>
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

  <!-- Barra de Navegação -->
  <nav class="navbar" role="navigation" aria-label="Menu principal">
    <div class="nav-links">
      <a href="telaprincipalc.php">Início</a>
      <a href="telaeditalcoordenador.php">Editais</a>
      <a href="listainscritos.php">Inscrições</a>
      <a href="painelestatistico.php">Acompanhamento</a>
      <a href="relatorios.php">Relatórios</a>
    </div>
  </nav>

  <div class="container">
    <h2 class="section-title">Editais em Aberto</h2>
    <div class="results-table">
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
            <tr><td colspan="6">Nenhum edital em aberto.</td></tr>
          <?php else: ?>
            <?php foreach ($editais as $edital): ?>
              <tr>
                <td><?php echo htmlspecialchars($edital['titulo']); ?></td>
                <td><?php echo htmlspecialchars($edital['unidade']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($edital['data_inicio'])); ?></td>
                <td><?php echo date('d/m/Y', strtotime($edital['data_termino'])); ?></td>
                <td><?php echo htmlspecialchars($edital['semestre']); ?></td>
                <td>
                  <a href="editar_edital.php?id=<?php echo $edital['id']; ?>" class="btn-secondary" style="padding:6px 16px; text-decoration:none; margin-right:5px;">
                    Editar
                  </a>
                  <a href="#" class="btn-excluir" style="padding:6px 16px; text-decoration:none;"
                     onclick="abrirModalExcluirEdital(<?php echo $edital['id']; ?>); return false;">
                    Excluir
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="modalExcluirEdital" class="modal" style="display:none;">
    <div class="modal-content confirmation-content">
      <div class="confirmation-icon">
        <i class="fas fa-exclamation-triangle" style="font-size:48px;color:#a80606;"></i>
      </div>
      <h2>Excluir Edital</h2>
      <p>Tem certeza que deseja excluir este edital?</p>
      <div style="margin-top:20px;">
        <button class="btn-excluir" id="btnConfirmarExclusao">Sim, excluir</button>
        <button class="btn-secondary" onclick="fecharModalExcluirEdital()">Cancelar</button>
      </div>
    </div>
  </div>
 <div class="pagination" role="navigation" aria-label="Paginação">
      <button aria-label="Primeira página">«</button>
      <button class="active" aria-current="page">1</button>
      <button aria-label="Última página">»</button>
    </div>
  <footer class="footer">
    <div class="footer-content">
      <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
      <p>Desenvolvido por Prisma</p>
    </div>
  </footer>
</body>
</html>
