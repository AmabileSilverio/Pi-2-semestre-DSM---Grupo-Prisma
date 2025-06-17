<?php
session_start();
include(__DIR__ . '/../conexao.php');

$id_professor = $_SESSION['id'] ?? null;
$tipo = $_SESSION['tipo'] ?? 'professor'; // ou 'coordenador'

$usuario = [
    'nome' => '',
    'email' => '',
    'tipo' => '',
    'rg' => '',
    'contato' => '',
    'matricula' => ''
];

if ($id_professor && $tipo) {
    if ($tipo === 'professor') {
        $sql = "SELECT nome, email, tipo_contrato, rg, contato, matricula FROM professor WHERE id = ?";
    } else {
        $sql = "SELECT nome, email FROM coordenador WHERE id = ?";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_professor);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc() ?: $usuario;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Inscrição - Fatec Itapira</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="../Assets/style.css" rel="stylesheet">
    <link href="../Assets/formulario.css" rel="stylesheet">
    <script src="../Assets/formulario.js"></script>
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
  <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
</div>
<span><?php echo htmlspecialchars($usuario['nome']); ?></span>
  <a href="index.php">
    <i class="fas fa-sign-out-alt"></i>
  </a>
</div>
  </div>
    <!-- Barra de Navegação e Busca -->
    <nav class="navbar" role="navigation" aria-label="Menu principal">
      <div class="nav-links">
      <a href="telaprincipal.php">Home</a>
      <a href="formulario.php">Inscrições</a>
      <a href="telaacompanhamento.php">Acompanhamento</a>
      <a href="relatorios_professor.php" class="active">Relatórios</a>
      <a href="telaedital.php">Edital</a>
      <a href="telaagenda.php">Agenda</a>
    </div>
    </nav>
    
    <div class="form-container">
        <!-- Indicador de Progresso -->
        <div class="progress-bar">
            <div class="step active" data-step="1">
                <span>1</span>
                <span class="step-title">Dados Pessoais</span>
            </div>
            <div class="step" data-step="2">
                <span>2</span>
                <span class="step-title">Informações Acadêmicas</span>
            </div>
            <div class="step" data-step="3">
                <span>3</span>
                <span class="step-title">Projeto</span>
            </div>
            <div class="step" data-step="4">
                <span>4</span>
                <span class="step-title">Termos de Uso</span>
            </div>
        </div>

        <form id="inscricaoForm" method="POST" action="/Pi2/Controller/formulario_hae.php" enctype="multipart/form-data">
    <!-- Etapa 1: Dados Pessoais -->
    <div class="form-step active" id="step1" data-step="1">
        <h2>Dados Pessoais</h2>
        <div class="form-row">
            <div class="form-column">
                <label for="nome">Professor(a):</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>">
                
                <label for="mail">E-mail:</label>
                <input type="email" id="mail" name="mail" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>">
                
                <label for="tipo_contrato">Contrato de Trabalho</label>
                <select name="tipo_contrato" id="tipo_contrato">
                    <option value="">Selecione...</option>
                    <option value="contrato_temporario" <?php if(($usuario['tipo_contrato'] ?? '') == 'contrato_temporario') echo 'selected'; ?>>Contrato Temporário</option>
                    <option value="contrato_efetivo" <?php if(($usuario['tipo_contrato'] ?? '') == 'contrato_efetivo') echo 'selected'; ?>>Contrato Efetivo</option>
                </select>
            </div>
            <div class="form-column">
                <label for="rg">RG:</label>
                <input type="text" id="rg" name="rg" value="<?php echo htmlspecialchars($usuario['rg'] ?? ''); ?>" >
                
                <label for="contato">Contato</label>
                <input type="tel" id="contato" name="contato" value="<?php echo htmlspecialchars($usuario['contato'] ?? ''); ?>">
                
                <label for="matricula">Nº Matrícula</label>
                <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($usuario['matricula'] ?? ''); ?>">
            </div>
        </div>
    </div>
            <!-- Etapa 2: Informações Acadêmicas -->
            <div class="form-step" id="step2" data-step="2">
        <h2>Informações Acadêmicas</h2>
        <div class="form-group">
            <label>Possui aula em outra fatec?</label>
            <div class="radio-container">
                <input type="radio" id="sim" name="aula_fatec" value="sim">
                <label for="sim" class="radio-label">Sim</label>
            </div>
            <div class="radio-container">
                <input type="radio" id="nao" name="aula_fatec" value="nao">
                <label for="nao" class="radio-label">Não</label>
            </div>
        </div>

        <div class="form-group">
            <label for="horas_disponiveis">Horas semanais disponíveis:</label>
            <input type="time" id="horas_disponiveis" name="horas_disponiveis" value="08:00">
        </div>

        <div class="form-group">
            <label for="tipo_hae">Tipo da HAE:</label>
            <select name="tipo_hae" id="tipo_hae">
                <option value="">Selecione...</option>
                <option value="estagio_supervisionado">Estágio Supervisionado</option>
                <option value="trabalho_graduacao">Trabalho de Graduação</option>
                <option value="iniciacao_cientifica">Iniciação Científica</option>
                <option value="divulgacao_cursos">Divulgação dos Cursos</option>
                <option value="administracao_academica">Administração Acadêmica</option>
                <option value="enade">Preparação para ENADE</option>
            </select>
        </div>

        <div class="form-group">
            <label for="curso">Curso:</label>
            <select name="curso" id="curso" required>
                <option value="">Selecione...</option>
                <option value="DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA">Desenvolvimento de Software Multiplataforma</option>
                <option value="GESTAO EMPRESARIAL">Gestão Empresarial</option>
                <option value="GESTÃO DA PRODUÇÃO INDUSTRIAL">Gestão da Produção Industrial</option>
            </select>
        </div>

        <div class="form-group">
            <label for="horas_solicitadas">Quantidade de horas solicitadas:</label>
           <input type="time" id="horas_solicitadas" name="horas_solicitadas" value="08:00">
        </div>
    </div>

           <div class="form-step" id="step3" data-step="3">
        <h2>Projeto</h2>
        <div class="form-group">
            <label for="unidade">Projeto de Interesse da Unidade:</label>
            <select name="unidade" id="unidade" required>
                <option value="">Selecione...</option>
                <option value="Americana">Americana</option>
                <option value="Araras">Araras</option>
                <option value="Campinas">Campinas</option>
                <option value="Itapira">Itapira</option>
                <option value="Mogi Mirim">Mogi Mirim</option>
                <option value="Santo André">Santo André</option>
            </select>
        </div>

        <div class="form-group">
            <label for="titulo">Título do projeto de interesse conforme Edital:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Digite o título do projeto conforme edital">
        </div>

        <div class="form-group">
            <label for="TituloP">Título do seu projeto:</label>
            <input type="text" id="TituloP" name="TituloP" placeholder="Digite o título do seu projeto">
        </div>

        <div class="form-group">
            <label for="metodologia">Metodologia do projeto:</label>
            <textarea id="metodologia" name="metodologia" rows="4" placeholder="Descreva a metodologia do projeto aqui..."></textarea>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição do projeto:</label>
            <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva seu projeto aqui..."></textarea>
        </div>

        <div class="form-group">
            <label for="horarios">Horários do Projeto</label>
            <div class="horarios-container">
                <div class="dia-horario">
                    <label>
                        <input type="checkbox" name="dias[]" value="segunda"> Segunda-feira
                    </label>
                    <div class="horario-inputs">
                        <input type="time" name="inicio_segunda" placeholder="Início">
                        <span>até</span>
                        <input type="time" name="fim_segunda" placeholder="Fim">
                    </div>
                </div>

                <div class="dia-horario">
                    <label>
                        <input type="checkbox" name="dias[]" value="terca"> Terça-feira
                    </label>
                    <div class="horario-inputs">
                        <input type="time" name="inicio_terca" placeholder="Início">
                        <span>até</span>
                        <input type="time" name="fim_terca" placeholder="Fim">
                    </div>
                </div>

                <div class="dia-horario">
                    <label>
                        <input type="checkbox" name="dias[]" value="quarta"> Quarta-feira
                    </label>
                    <div class="horario-inputs">
                        <input type="time" name="inicio_quarta" placeholder="Início">
                        <span>até</span>
                        <input type="time" name="fim_quarta" placeholder="Fim">
                    </div>
                </div>

                <div class="dia-horario">
                    <label>
                        <input type="checkbox" name="dias[]" value="quinta"> Quinta-feira
                    </label>
                    <div class="horario-inputs">
                        <input type="time" name="inicio_quinta" placeholder="Início">
                        <span>até</span>
                        <input type="time" name="fim_quinta" placeholder="Fim">
                    </div>
                </div>

                <div class="dia-horario">
                    <label>
                        <input type="checkbox" name="dias[]" value="sexta"> Sexta-feira
                    </label>
                    <div class="horario-inputs">
                        <input type="time" name="inicio_sexta" placeholder="Início">
                        <span>até</span>
                        <input type="time" name="fim_sexta" placeholder="Fim">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="proposta">Anexar Proposta de Projeto (PDF):</label>
            <input type="file" id="proposta" name="proposta" accept=".pdf" required>
        </div>
    </div>

    <!-- Etapa 4: Termos de Uso -->
    <div class="form-step" id="step4" data-step="4">
        <h2>Termos de Uso</h2>
        <div class="terms-container">
            <h3>Termos e Condições para Participação em H.A.E</h3>
            
            <div class="terms-content">
                <h4>1. Objetivo</h4>
                <p>O presente termo estabelece as condições para participação nas atividades de Hora de Atividade de Ensino (H.A.E) na Fatec Itapira.</p>

                <h4>2. Compromissos do Professor</h4>
                <p>2.1. O professor se compromete a:</p>
                <ul>
                    <li>Dedicar o tempo da H.A.E exclusivamente às atividades acadêmicas</li>
                    <li>Manter registro detalhado das atividades realizadas</li>
                    <li>Apresentar relatórios periódicos de suas atividades</li>
                    <li>Participar das reuniões e eventos relacionados à H.A.E</li>
                </ul>

                <h4>3. Responsabilidades</h4>
                <p>3.1. O professor é responsável por:</p>
                <ul>
                    <li>Manter a confidencialidade das informações</li>
                    <li>Zelar pelo patrimônio da instituição</li>
                    <li>Cumprir os prazos estabelecidos</li>
                    <li>Comunicar qualquer impedimento à coordenação</li>
                </ul>

                <h4>4. Penalidades</h4>
                <p>O não cumprimento dos termos poderá resultar em:</p>
                <ul>
                    <li>Suspensão da participação em H.A.E</li>
                    <li>Cancelamento de futuras solicitações</li>
                    <li>Medidas disciplinares conforme regimento</li>
                </ul>
            </div>

            <div class="checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">Estou ciente e concordo com os termos de uso</label>
            </div>
        </div>
    </div>
            <!-- Etapa 4: Termos de Uso -->
            <div class="form-step" id="step4" data-step="4">
                <h2>Termos de Uso</h2>
                <div class="terms-container">
                    <h3>Termos e Condições para Participação em H.A.E</h3>
                    
                    <div class="terms-content">
                        <h4>1. Objetivo</h4>
                        <p>O presente termo estabelece as condições para participação nas atividades de Hora de Atividade de Ensino (H.A.E) na Fatec Itapira.</p>

                        <h4>2. Compromissos do Professor</h4>
                        <p>2.1. O professor se compromete a:</p>
                        <ul>
                            <li>Dedicar o tempo da H.A.E exclusivamente às atividades acadêmicas</li>
                            <li>Manter registro detalhado das atividades realizadas</li>
                            <li>Apresentar relatórios periódicos de suas atividades</li>
                            <li>Participar das reuniões e eventos relacionados à H.A.E</li>
                        </ul>

                        <h4>3. Responsabilidades</h4>
                        <p>3.1. O professor é responsável por:</p>
                        <ul>
                            <li>Manter a confidencialidade das informações</li>
                            <li>Zelar pelo patrimônio da instituição</li>
                            <li>Cumprir os prazos estabelecidos</li>
                            <li>Comunicar qualquer impedimento à coordenação</li>
                        </ul>

                        <h4>4. Penalidades</h4>
                        <p>O não cumprimento dos termos poderá resultar em:</p>
                        <ul>
                            <li>Suspensão da participação em H.A.E</li>
                            <li>Cancelamento de futuras solicitações</li>
                            <li>Medidas disciplinares conforme regimento</li>
                        </ul>
                    </div>

                    <div class="checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Estou ciente e concordo com os termos de uso</label>
                    </div>
                </div>
            </div>

            <!-- Botões de Navegação -->
            <div class="button-group">
                <button type="button" class="btn-prev" onclick="prevStep()">Anterior</button>
                <button type="button" class="btn-next" onclick="nextStep()">Próximo</button>
                <button type="submit" class="submit-btn">Enviar Inscrição</button>
            </div>
        </form>

        <div class="logo-containertabela">
            <img src="http://www.fatecsp.br/img/logos_cps_gov.jpg" alt="Logo CPS">
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
          <img src="../Assets/Logo prisma2.png" alt="Logo Governo do Estado de São Paulo">
          <p>Desenvolvido por Prisma</p>
        </div>
    </footer>

    <!-- Modal de Confirmação -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content confirmation-content">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Inscrição Enviada com Sucesso!</h2>
            <p>Sua inscrição foi recebida com sucesso!</p>
            <p>Uma confirmação foi enviada para seu e-mail: <strong id="userEmail"></strong></p>
            <p>Você pode acompanhar o status da sua inscrição na página de Acompanhamento.</p>
            <button class="btn-primary" onclick="closeModal()">OK</button>
        </div>
    </div>
</body>
</html>