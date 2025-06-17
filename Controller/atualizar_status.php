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
        if (!isset($data['horas_aprovadas']) || !isset($data['data_inicio']) || !isset($data['data_fim'])) {
            throw new Exception('Dados de aprovação incompletos');
        }
        $query .= ", horas_aprovadas = :horas_aprovadas, data_inicio = :data_inicio, data_fim = :data_fim";
        $params[':horas_aprovadas'] = $data['horas_aprovadas'];
        $params[':data_inicio'] = $data['data_inicio'];
        $params[':data_fim'] = $data['data_fim'];
        
        // Buscar o ID do professor da inscrição
        $stmt = $pdo->prepare("SELECT id_professor FROM inscricoes_hae WHERE id = :id");
        $stmt->execute([':id' => $data['id']]);
        $inscricao = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$inscricao || !$inscricao['id_professor']) {
            throw new Exception('ID do professor não encontrado para esta inscrição');
        }
        
        // Inserir na tabela agenda_hae
        $stmt = $pdo->prepare("INSERT INTO agenda_hae (id_inscricao, id_professor, data_inicio, data_fim, hora_inicio, status) 
                              VALUES (:id_inscricao, :id_professor, :data_inicio, :data_fim, '08:00:00', 'Em Andamento')");
        $stmt->execute([
            ':id_inscricao' => $data['id'],
            ':id_professor' => $inscricao['id_professor'],
            ':data_inicio' => $data['data_inicio'],
            ':data_fim' => $data['data_fim']
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