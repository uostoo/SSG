<?php
include 'db.php';

// Recupera o ID do serviço a ser excluído
$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $id_servico = $data->id;

    // Consulta para excluir o serviço
    $sql = "DELETE FROM servicos WHERE id = :id_servico";
    $stmt = $pdo->prepare($sql);

    // Executa a consulta de exclusão
    $stmt->execute([':id_servico' => $id_servico]);

    // Verifica se a exclusão foi bem-sucedida
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Serviço excluído com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o serviço.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID do serviço não fornecido.']);
}
?>
