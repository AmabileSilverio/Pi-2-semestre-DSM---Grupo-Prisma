<?php
header('Content-Type: application/json');

session_start();
include(__DIR__ . '/../conexao.php');

$id_usuario = $_SESSION['id'] ?? null;
$tipo = $_SESSION['tipo'] ?? 'professor';

if (!$id_usuario) {
    echo json_encode([]);
    exit;
}

try {
    // Conectar ao banco de dados
    $host = 'localhost';
    $dbname = 'sistema_hae';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $filtros = [];
    $params = [];

    if ($tipo === 'professor') {
        $filtros[] = "i.id_professor = ?";
        $params[] = $id_usuario;
    }

    if (isset($_GET['unidade']) && !empty($_GET['unidade'])) {
        $filtros[] = "i.projeto_unidade = ?";
        $params[] = $_GET['unidade'];
    }

    if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
        $filtros[] = "i.tipo_hae = ?";
        $params[] = $_GET['tipo'];
    }

    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $filtros[] = "i.status = ?";
        $params[] = $_GET['status'];
    }

    $query = "SELECT i.id, p.nome AS nome_professor, i.tipo_hae, i.projeto_unidade, i.status
              FROM inscricoes_hae i
              JOIN professor p ON i.id_professor = p.id";

    if (count($filtros) > 0) {
        $query .= " WHERE " . implode(" AND ", $filtros);
    }

    // Ordenar por data de envio (mais recente primeiro)
    $query .= " ORDER BY data_envio DESC";

    // Preparar e executar a query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar os resultados
    echo json_encode([
        'status' => 'ok',
        'data' => $inscricoes
    ]);
} catch (PDOException $e) {
    error_log("Erro ao buscar inscrições: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'mensagem' => 'Erro ao buscar inscrições: ' . $e->getMessage()
    ]);
}
?>
