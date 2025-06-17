<?php
// Habilitar exibição de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Garantir que a saída será JSON
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../conexao.php';

    // Log para debug
    error_log("Iniciando busca de estatísticas");

    // Verificar se as tabelas existem
    $tables = $conn->query("SHOW TABLES LIKE 'projetos_estatisticos'");
    if ($tables->num_rows == 0) {
        throw new Exception("Tabela projetos_estatisticos não encontrada");
    }

    $tables = $conn->query("SHOW TABLES LIKE 'inscricoes_hae'");
    if ($tables->num_rows == 0) {
        throw new Exception("Tabela inscricoes_hae não encontrada");
    }

    // Verificar se a coluna horas_aprovadas existe
    $columns = $conn->query("SHOW COLUMNS FROM inscricoes_hae LIKE 'horas_aprovadas'");
    if ($columns->num_rows == 0) {
        throw new Exception("Coluna horas_aprovadas não encontrada na tabela inscricoes_hae");
    }

    // --- DEFINA OS FILTROS PRIMEIRO ---
    $curso = isset($_GET['curso']) ? $conn->real_escape_string($_GET['curso']) : null;
    $tipo_hae = isset($_GET['tipo_hae']) ? $conn->real_escape_string($_GET['tipo_hae']) : null;
    $numero = isset($_GET['numero']) ? $conn->real_escape_string($_GET['numero']) : null;

    $where = [];
    if ($curso) $where[] = "curso = '$curso'";
    if ($tipo_hae) $where[] = "tipo_hae = '$tipo_hae'";
    if ($numero) $where[] = "id = '$numero'";

    $whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    // --- SÓ DEPOIS MONTE AS QUERIES ---
    // Buscar estatísticas por curso
    $query_cursos = "SELECT 
        i.curso,
        COUNT(*) as total,
        SUM(CASE WHEN i.status = 'aprovado' THEN 1 ELSE 0 END) as aprovados,
        SUM(CASE WHEN i.status = 'rejeitado' THEN 1 ELSE 0 END) as rejeitados,
        SUM(CASE WHEN i.status = 'inscrito' THEN 1 ELSE 0 END) as inscritos,
        COALESCE(SUM(CASE WHEN i.status = 'aprovado' THEN i.horas_aprovadas ELSE 0 END), 0) as total_horas
    FROM inscricoes_hae i
    $whereSQL
    GROUP BY i.curso";
    
    error_log("Executando query de cursos: " . $query_cursos);
    $result_cursos = $conn->query($query_cursos);
    
    if (!$result_cursos) {
        throw new Exception("Erro na query de cursos: " . $conn->error);
    }
    
    $estatisticas_cursos = [];
    while($row = $result_cursos->fetch_assoc()) {
        $estatisticas_cursos[] = $row;
    }
    error_log("Dados de cursos obtidos: " . json_encode($estatisticas_cursos));

    // Buscar estatísticas por tipo de HAE
    $query_tipo_hae = "SELECT 
        i.tipo_hae,
        COUNT(*) as total,
        SUM(CASE WHEN i.status = 'aprovado' THEN 1 ELSE 0 END) as aprovados,
        SUM(CASE WHEN i.status = 'rejeitado' THEN 1 ELSE 0 END) as rejeitados,
        SUM(CASE WHEN i.status = 'inscrito' THEN 1 ELSE 0 END) as inscritos,
        COALESCE(SUM(CASE WHEN i.status = 'aprovado' THEN i.horas_aprovadas ELSE 0 END), 0) as total_horas
    FROM inscricoes_hae i
    $whereSQL
    GROUP BY i.tipo_hae";
    
    error_log("Executando query de tipo HAE: " . $query_tipo_hae);
    $result_tipo_hae = $conn->query($query_tipo_hae);
    
    if (!$result_tipo_hae) {
        throw new Exception("Erro na query de tipo HAE: " . $conn->error);
    }
    
    $estatisticas_tipo_hae = [];
    while($row = $result_tipo_hae->fetch_assoc()) {
        $estatisticas_tipo_hae[] = $row;
    }
    error_log("Dados de tipo HAE obtidos: " . json_encode($estatisticas_tipo_hae));

    // Buscar totais gerais
    $query_total = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'aprovado' THEN 1 ELSE 0 END) as aprovados,
        SUM(CASE WHEN status = 'rejeitado' THEN 1 ELSE 0 END) as rejeitados,
        SUM(CASE WHEN status = 'inscrito' THEN 1 ELSE 0 END) as inscritos
    FROM inscricoes_hae
    $whereSQL";
    $result_total = $conn->query($query_total);

    if (!$result_total) {
        throw new Exception("Erro na query de totais: " . $conn->error);
    }

    $estatisticas_total = $result_total->fetch_assoc();
    error_log("Dados de totais obtidos: " . json_encode($estatisticas_total));

    // Retornar todos os dados em formato JSON
    $response = [
        'cursos' => $estatisticas_cursos,
        'tipo_hae' => $estatisticas_tipo_hae,
        'total' => $estatisticas_total
    ];
    
    error_log("Enviando resposta: " . json_encode($response));
    echo json_encode($response);

} catch (Exception $e) {
    error_log("Erro em buscar_estatisticas.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}
?>