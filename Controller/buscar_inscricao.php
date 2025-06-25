<?php
header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID da inscrição não fornecido");
    }

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "sistema_hae";

    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT 
        i.*, 
        p.nome AS nome_professor, 
        e.id AS id_edital_real, 
        e.titulo AS titulo_projeto, 
        e.unidade AS projeto_unidade,
        e.data_inicio,
        e.data_termino
    FROM inscricoes_hae i
    LEFT JOIN professor p ON i.id_professor = p.id
    LEFT JOIN editais_hae e ON i.id_edital = e.id
    WHERE i.id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Para compatibilidade com o JS, envie também id_edital = id_edital_real
        $row['id_edital'] = $row['id_edital_real'];
        echo json_encode([
            'status' => 'ok',
            'data' => $row
        ]);
    } else {
        throw new Exception("Inscrição não encontrada");
    }

} catch (Exception $e) {
    error_log("Erro ao buscar inscrição: " . $e->getMessage());
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Erro ao buscar inscrição: ' . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>
