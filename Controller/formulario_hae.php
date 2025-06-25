<?php
// Desabilita a exibição de erros na saída
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Define o tipo de conteúdo como JSON
header('Content-Type: application/json');

try {
    // Log para debug
    error_log("Requisição recebida em formulario_hae.php");
    error_log("POST: " . print_r($_POST, true));
    error_log("FILES: " . print_r($_FILES, true));

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "sistema_hae";

    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Verifica se todos os campos necessários estão presentes
    $campos_obrigatorios = [
        'nome', 'mail', 'tipo_contrato', 'rg', 'contato', 'matricula', 
        'aula_fatec', 'horas_disponiveis', 'tipo_hae', 'horas_solicitadas',
        'TituloP', 'metodologia', 'descricao', 'curso', 'id_edital'
    ];
    foreach ($campos_obrigatorios as $campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            throw new Exception("Campo obrigatório não preenchido: " . $campo);
        }
    }

    // Escapar e coletar dados
    $nome = $_POST['nome'];
    $email = $_POST['mail'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $rg = $_POST['rg'];
    $contato = $_POST['contato'];
    $matricula = $_POST['matricula'];
    $aula_outra_fatec = $_POST['aula_fatec'];
    $horas_disponiveis = $_POST['horas_disponiveis'];
    $tipo_hae = $_POST['tipo_hae'];
    $horas_solicitadas = $_POST['horas_solicitadas'];
    $titulo_projeto = $_POST['TituloP'];
    $metodologia = $_POST['metodologia'];
    $descricao = $_POST['descricao'];
    $curso = $_POST['curso'];
    $dias = isset($_POST['dias']) ? implode(',', $_POST['dias']) : '';
    $horarios = json_encode([
        "segunda" => [$_POST['inicio_segunda'] ?? '', $_POST['fim_segunda'] ?? ''],
        "terca" => [$_POST['inicio_terca'] ?? '', $_POST['fim_terca'] ?? ''],
        "quarta" => [$_POST['inicio_quarta'] ?? '', $_POST['fim_quarta'] ?? ''],
        "quinta" => [$_POST['inicio_quinta'] ?? '', $_POST['fim_quinta'] ?? ''],
        "sexta" => [$_POST['inicio_sexta'] ?? '', $_POST['fim_sexta'] ?? '']
    ]);

    $aceite_termos = isset($_POST['terms']) ? 1 : 0;

    // Buscar o ID do professor pelo email
    $stmt = $conn->prepare("SELECT id FROM professor WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $professor = $result->fetch_assoc();
    
    if (!$professor) {
        throw new Exception("Professor não encontrado com o email fornecido");
    }
    
    $id_professor = $professor['id'];

    // Upload de arquivo
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $proposta_nome = $_FILES['proposta']['name'] ?? '';
    $proposta_tmp = $_FILES['proposta']['tmp_name'] ?? '';
    $proposta_path = '';
    
    if (!empty($proposta_nome) && !empty($proposta_tmp)) {
        $proposta_path = $uploadDir . time() . "_" . basename($proposta_nome);
        if (!move_uploaded_file($proposta_tmp, $proposta_path)) {
            throw new Exception("Erro ao fazer upload do arquivo");
        }
    }

    // Anexo
    $anexo_nome = '';
    if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads_inscricoes/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $anexo_nome = time() . '_' . basename($_FILES['anexo']['name']);
        $anexo_caminho = $uploadDir . $anexo_nome;
        move_uploaded_file($_FILES['anexo']['tmp_name'], $anexo_caminho);
    }

    error_log("Nome do anexo: " . $anexo_nome);

    // Buscar unidade do edital
    $id_edital = $_POST['id_edital'];
    $stmt = $conn->prepare("SELECT unidade, titulo FROM editais_hae WHERE id = ?");
    $stmt->bind_param("i", $id_edital);
    $stmt->execute();
    $result = $stmt->get_result();
    $edital = $result->fetch_assoc();
    if (!$edital) {
        throw new Exception("Edital não encontrado.");
    }
    $projeto_unidade = $edital['unidade'];
    $titulo_editado = $edital['titulo'];

    // Inserir no banco
    $stmt = $conn->prepare("INSERT INTO inscricoes_hae (
        nome, email, tipo_contrato, rg, contato, matricula, aula_outra_fatec, 
        horas_disponiveis, tipo_hae, horas_solicitadas, projeto_unidade, 
        titulo_editado, titulo_projeto, metodologia, descricao, dias, horarios, 
        proposta_nome, proposta_path, aceite_termos, id_professor, curso, anexo, id_edital
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Erro na preparação da query: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssssssssiissi",
        $nome, $email, $tipo_contrato, $rg, $contato, $matricula,
        $aula_outra_fatec, $horas_disponiveis, $tipo_hae, $horas_solicitadas,
        $projeto_unidade, $titulo_editado, $titulo_projeto, $metodologia, $descricao,
        $dias, $horarios, $proposta_nome, $proposta_path, $aceite_termos, $id_professor, $curso, $anexo_nome, $id_edital
    );

    if (!$stmt->execute()) {
        throw new Exception("Erro ao executar a query: " . $stmt->error);
    }

    // Envio do e-mail de confirmação
    $nome_professor = $_POST['nome'];
    $email = $_POST['mail'];
    $assunto = "Confirmação de Inscrição HAE";
    $mensagem = "Olá, $nome_professor!\n\nSua inscrição foi recebida com sucesso.\n\nAtenciosamente,\nEquipe HAE";
    $cabecalhos = "From: sistema@seudominio.com\r\n";
    $cabecalhos .= "Reply-To: sistema@seudominio.com\r\n";
    $cabecalhos .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($email_professor, $assunto, $mensagem, $cabecalhos);

    echo json_encode([
        "status" => "ok",
        "mensagem" => "Inscrição realizada com sucesso!"
    ]);

} catch (Exception $e) {
    error_log("Erro no formulário: " . $e->getMessage());
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao processar o formulário: " . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}

?>
