<?php
header('Content-Type: application/json');

try {
    // Receber dados do POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['status'])) {
        throw new Exception('Dados incompletos');
    }
    
    // Conectar ao banco de dados
    $pdo = new PDO('mysql:host=localhost;dbname=sistema_hae', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Iniciar transação
    $pdo->beginTransaction();
    
    // Preparar a query base
    $query = "UPDATE inscricoes_hae SET status = :status";
    $params = [':status' => $data['status'], ':id' => $data['id']];
    
    // Adicionar campos específicos baseado no status
    if ($data['status'] === 'Aprovado') {
        if (!isset($data['horas_aprovadas'])) {
            throw new Exception('Dados de aprovação incompletos');
        }
        $query .= ", horas_aprovadas = :horas_aprovadas";
        $params[':horas_aprovadas'] = $data['horas_aprovadas'];

        // Buscar o ID do professor e do edital da inscrição
        $stmt = $pdo->prepare("SELECT id_professor, id_edital FROM inscricoes_hae WHERE id = :id");
        $stmt->execute([':id' => $data['id']]);
        $inscricao = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inscricao || !$inscricao['id_professor'] || !$inscricao['id_edital']) {
            throw new Exception('ID do professor ou edital não encontrado para esta inscrição');
        }

        // Buscar as datas do edital
        $stmt = $pdo->prepare("SELECT data_inicio, data_termino FROM editais_hae WHERE id = :id_edital");
        $stmt->execute([':id_edital' => $inscricao['id_edital']]);
        $edital = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$edital) {
            throw new Exception('Edital não encontrado');
        }

        $data_inicio = $edital['data_inicio'];
        $data_fim = $edital['data_termino'];

        // Inserir na tabela agenda_hae
        $stmt = $pdo->prepare("INSERT INTO agenda_hae (id_inscricao, id_professor, data_inicio, data_fim, hora_inicio, status) 
                              VALUES (:id_inscricao, :id_professor, :data_inicio, :data_fim, '08:00:00', 'Em Andamento')");
        $stmt->execute([
            ':id_inscricao' => $data['id'],
            ':id_professor' => $inscricao['id_professor'],
            ':data_inicio' => $data_inicio,
            ':data_fim' => $data_fim
        ]);
    } elseif ($data['status'] === 'Rejeitado') {
        if (!isset($data['motivo_rejeicao'])) {
            throw new Exception('Motivo da rejeição não informado');
        }
        $query .= ", motivo_rejeicao = :motivo_rejeicao";
        $params[':motivo_rejeicao'] = $data['motivo_rejeicao'];
    }
    
    $query .= " WHERE id = :id";
    
    // Executar a query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Inscrição não encontrada');
    }
    
    // Commit da transação
    $pdo->commit();
    
    echo json_encode([
        'status' => 'ok',
        'mensagem' => 'Status atualizado com sucesso'
    ]);
    
} catch (Exception $e) {
    // Rollback em caso de erro
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    
    http_response_code(400);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => $e->getMessage()
    ]);
}
