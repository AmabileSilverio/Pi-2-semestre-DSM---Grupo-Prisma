<?php
header('Content-Type: application/json');
require_once '../conexao.php';

try {
    if (empty($_POST['id']) || empty($_POST['parecer'])) {
        throw new Exception('Dados incompletos.');
    }
    $id = intval($_POST['id']);
    $parecer = trim($_POST['parecer']);

    $stmt = $conn->prepare("UPDATE relatorios_hae SET status = 'Correção Solicitada', parecer_coordenador = ?, data_analise = NOW() WHERE id = ?");
    $stmt->bind_param("si", $parecer, $id);

    if (!$stmt->execute()) {
        throw new Exception('Erro ao solicitar correção.');
    }

    echo json_encode(['status' => 'ok']);
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>