<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once(__DIR__ . '/../conexao.php');

$id_usuario = $_SESSION['id'] ?? null;
$tipo = $_SESSION['tipo'] ?? null;
$nome = 'Usuário';

if ($id_usuario && $tipo) {
    if ($tipo === 'professor') {
        $sql = "SELECT nome FROM professor WHERE id = ?";
    } elseif ($tipo === 'coordenador') {
        $sql = "SELECT nome FROM coordenador WHERE id = ?";
    }
    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $nome = $user['nome'] ?? 'Usuário';
    }
}
?>