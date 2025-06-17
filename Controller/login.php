<?php
session_start();
include '../model/Coordenador.php';
include '../model/Professor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $coord = new Coordenador();
    $prof = new Professor();

    if ($dados = $coord->login($email, $senha)) {
        $_SESSION['id'] = $dados['id'];
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['tipo'] = 'coordenador';
        $_SESSION['email'] = $dados['email'];
        header("Location: /Pi2/view/telaprincipalc.php");
        exit;
    } elseif ($dados = $prof->login($email, $senha)) {
        $_SESSION['id'] = $dados['id'];
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['tipo'] = 'professor';
        $_SESSION['email'] = $dados['email'];
        
        // Log para debug
        error_log("Login bem-sucedido - Professor: " . print_r($_SESSION, true));
        
        header("Location: /Pi2/view/telaprincipal.php");
        exit;
    } else {
        error_log("Tentativa de login falhou - Email: " . $email);
        echo "<script>alert('Email ou senha incorretos!'); window.location.href='/Pi2/view/index.php';</script>";
    }
}