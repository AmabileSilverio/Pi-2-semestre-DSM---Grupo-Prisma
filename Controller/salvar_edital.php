<?php
session_start();
include(__DIR__ . '/../conexao.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $unidade = $_POST['unidade'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_termino = $_POST['data_termino'] ?? '';
    $semestre = $_POST['semestre'] ?? '';
    $id_edital = $_POST['id'] ?? null;

    if ($id_edital) {
        // Atualizar edital existente
        $stmt = $conn->prepare("UPDATE editais_hae SET titulo=?, unidade=?, data_inicio=?, data_termino=?, semestre=? WHERE id=?");
        $stmt->bind_param("sssssi", $titulo, $unidade, $data_inicio, $data_termino, $semestre, $id_edital);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao atualizar edital.']);
        }
    } else {
        // Cadastrar novo edital
        $stmt = $conn->prepare("INSERT INTO editais_hae (titulo, unidade, data_inicio, data_termino, semestre) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $titulo, $unidade, $data_inicio, $data_termino, $semestre);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao cadastrar edital.']);
        }
    }
    exit;
}
echo json_encode(['status' => 'erro', 'mensagem' => 'Requisição inválida.']);
?>
