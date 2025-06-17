<?php
header('Content-Type: application/json');

// Verifica se o ID da inscrição foi fornecido
if (!isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'mensagem' => 'ID da inscrição não fornecido']);
    exit;
}

$id = $_POST['id'];

try {
    // Conectar ao banco de dados
    $host = 'localhost';
    $dbname = 'sistema_hae';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Atualizar o status da inscrição para "Em Análise"
    $stmt = $pdo->prepare("UPDATE inscricoes_hae SET status = 'Em Análise' WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        // Buscar os dados da inscrição para enviar por email
        $stmt = $pdo->prepare("SELECT * FROM inscricoes_hae WHERE id = ?");
        $stmt->execute([$id]);
        $inscricao = $stmt->fetch(PDO::FETCH_ASSOC);

        // Configurar o email
        $to = "coordenador@fatecitapira.edu.br"; // Substitua pelo email do coordenador
        $subject = "Nova Inscrição HAE para Análise - #" . $id;
        
        // Montar o corpo do email
        $message = "Nova inscrição HAE enviada para análise:\n\n";
        $message .= "ID da Inscrição: " . $id . "\n";
        $message .= "Professor: " . $inscricao['nome'] . "\n";
        $message .= "Tipo HAE: " . $inscricao['tipo_hae'] . "\n";
        $message .= "Projeto: " . $inscricao['projeto_unidade'] . "\n";
        $message .= "Data de Envio: " . $inscricao['data_envio'] . "\n\n";
        $message .= "Acesse o sistema para mais detalhes.";

        $headers = "From: sistema@fatecitapira.edu.br\r\n";
        $headers .= "Reply-To: " . $inscricao['email'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Enviar o email
        if(mail($to, $subject, $message, $headers)) {
            echo json_encode(['status' => 'success', 'mensagem' => 'Inscrição enviada para análise com sucesso']);
        } else {
            // Se o email falhar, ainda retornamos sucesso pois a inscrição foi atualizada
            echo json_encode(['status' => 'success', 'mensagem' => 'Inscrição enviada para análise com sucesso (email não enviado)']);
        }
    } else {
        echo json_encode(['status' => 'error', 'mensagem' => 'Inscrição não encontrada']);
    }
} catch (PDOException $e) {
    error_log("Erro ao enviar inscrição para coordenador: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao processar a solicitação']);
}
?> 