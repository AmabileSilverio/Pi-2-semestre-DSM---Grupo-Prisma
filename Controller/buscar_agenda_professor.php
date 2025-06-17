<?php
session_start();
header('Content-Type: application/json');

// Log para debug
error_log("Sessão atual: " . print_r($_SESSION, true));

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'professor') {
    error_log("Erro de autenticação - Sessão: " . print_r($_SESSION, true));
    http_response_code(401);
    echo json_encode(['status' => 'error', 'mensagem' => 'Usuário não autenticado']);
    exit;
}

$id_professor = $_SESSION['id'];

try {
    // Conectar ao banco de dados
    $pdo = new PDO('mysql:host=localhost;dbname=sistema_hae', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Construir a query base
    $query = "SELECT a.*, i.titulo_projeto, i.tipo_hae, i.horas_aprovadas, i.metodologia, i.descricao 
              FROM agenda_hae a 
              INNER JOIN inscricoes_hae i ON a.id_inscricao = i.id 
              WHERE a.id_professor = :id_professor";
    
    $params = [':id_professor' => $id_professor];

    // Adicionar filtros se fornecidos
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $query .= " AND a.status = :status";
        $params[':status'] = $_GET['status'];
    }

    if (isset($_GET['data']) && !empty($_GET['data'])) {
        $query .= " AND DATE(a.data_inicio) = :data";
        $params[':data'] = $_GET['data'];
    }

    // Ordenar por data de início
    $query .= " ORDER BY a.data_inicio DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatar as datas e horas
    foreach ($projetos as &$projeto) {
        $projeto['data_inicio'] = date('d/m/Y', strtotime($projeto['data_inicio']));
        $projeto['data_fim'] = date('d/m/Y', strtotime($projeto['data_fim']));
        $projeto['hora_inicio'] = date('H:i', strtotime($projeto['hora_inicio']));
    }

    echo json_encode([
        'status' => 'ok',
        'data' => $projetos
    ]);

} catch (PDOException $e) {
    error_log("Erro no banco de dados: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'mensagem' => 'Erro ao buscar projetos: ' . $e->getMessage()
    ]);
} 