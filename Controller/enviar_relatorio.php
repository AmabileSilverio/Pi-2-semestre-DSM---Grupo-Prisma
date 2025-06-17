<?php
header('Content-Type: application/json');
require_once '../conexao.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    if (empty($_POST['id_inscricao']) || empty($_POST['descricaoRelatorio'])) {
        throw new Exception('Dados incompletos.');
    }
    $descricao = trim($_POST['descricaoRelatorio']);
    $id_inscricao = $_POST['id_inscricao'];

    // Upload do anexo
    $anexo_nome = '';
    if (isset($_FILES['anexoRelatorio']) && $_FILES['anexoRelatorio']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads_relatorios/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $anexo_nome = time() . '_' . basename($_FILES['anexoRelatorio']['name']);
        $anexo_caminho = $uploadDir . $anexo_nome;
        if (!move_uploaded_file($_FILES['anexoRelatorio']['tmp_name'], $anexo_caminho)) {
            throw new Exception('Erro ao salvar o anexo.');
        }
    }

    // Insere o relatório na tabela relatorios_hae
    $stmt = $conn->prepare("INSERT INTO relatorios_hae (id_inscricao, descricao, anexo, status) VALUES (?, ?, ?, 'Pendente')");
    $stmt->bind_param("iss", $id_inscricao, $descricao, $anexo_nome);

    if (!$stmt->execute()) {
        throw new Exception('Erro ao salvar relatório.');
    }

    echo json_encode(['status' => 'ok']);
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}

error_log('POST: ' . print_r($_POST, true));
?>