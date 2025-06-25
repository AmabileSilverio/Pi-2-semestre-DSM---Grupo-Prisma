<?php
header('Content-Type: application/json');
require_once '../conexao.php';

try {
    if (empty($_POST['id_relatorio']) || empty($_POST['descricaoReenvio'])) {
        throw new Exception('Dados incompletos.');
    }
    $id = intval($_POST['id_relatorio']);
    $descricao = trim($_POST['descricaoReenvio']);

    // Upload do novo anexo
    $anexo_nome = '';
    if (isset($_FILES['anexoReenvio']) && $_FILES['anexoReenvio']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads_relatorios/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $anexo_nome = time() . '_' . basename($_FILES['anexoReenvio']['name']);
        $anexo_caminho = $uploadDir . $anexo_nome;
        if (!move_uploaded_file($_FILES['anexoReenvio']['tmp_name'], $anexo_caminho)) {
            throw new Exception('Erro ao salvar o anexo.');
        }
    }

    // Atualiza o relatório
    $sql = "UPDATE relatorios_hae SET descricao = ?, status = 'Pendente', parecer_coordenador = NULL";
    if ($anexo_nome) {
        $sql .= ", anexo = ?";
    }
    $sql .= " WHERE id = ?";
    $stmt = $conn->prepare($anexo_nome ?
        $sql : str_replace(", anexo = ?", "", $sql));
    if ($anexo_nome) {
        $stmt->bind_param("ssi", $descricao, $anexo_nome, $id);
    } else {
        $stmt->bind_param("si", $descricao, $id);
    }

    if (!$stmt->execute()) {
        throw new Exception('Erro ao atualizar relatório.');
    }

    echo json_encode(['status' => 'ok']);
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
