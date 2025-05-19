<?php
include 'db.php';
session_start();

header('Content-Type: application/json'); // Garante que o retorno seja JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true); // Lê o corpo da requisição

    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID do agendamento não enviado.']);
        exit();
    }

    $id = $input['id'];

    // Exemplo de lógica: Atualizar o status do agendamento
    $sql = "UPDATE agendamentos SET status = 'aceito' WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([':id' => $id])) {
        echo json_encode(['success' => true, 'message' => 'Agendamento aceito com sucesso.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao aceitar o agendamento.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido.']);
}
?>
