<?php

session_start();
include(__DIR__ . '/../conexao.php');

// Verifica se o ID do edital foi passado
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../View/listaeditalcoordenador.php?erro=1");
    exit;
}

// Exclui o edital do banco de dados
$stmt = $conn->prepare("DELETE FROM editais_hae WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../View/listaeditalcoordenador.php?excluido=1");
    exit;
} else {
    header("Location: ../View/listaeditalcoordenador.php?erro=1");
    exit;
    }
?>
