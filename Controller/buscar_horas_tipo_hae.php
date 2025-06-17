<?php
require_once 'conexao.php';

try {
    // Buscar horas aprovadas por tipo de HAE
    $query = "SELECT 
        tipo_hae,
        SUM(horas_solicitadas) as total_horas
    FROM projetos_estatisticos
    WHERE status = 'aprovado'
    GROUP BY tipo_hae
    ORDER BY total_horas DESC";
    
    $result = $conn->query($query);
    
    $dados = [];
    while($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }

    // Retornar dados em formato JSON
    echo json_encode($dados);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}
?> 