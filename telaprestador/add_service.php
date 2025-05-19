<?php
include 'db.php';
session_start(); // Certifique-se de que a sessão esteja iniciada

// Verifique se o usuário está logado e se o ID do prestador existe na sessão
if (!isset($_SESSION['id_prestador'])) {
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar logado como prestador para adicionar um serviço.']);
    exit();
}

// O ID do prestador é recuperado da sessão
$id_prestador = $_SESSION['id_prestador'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do serviço via POST
    $nome_do_servico = $_POST['nome_do_servico'];
    $descricao = $_POST['descricao'];
    $valorhora = $_POST['valorhora'];
    $telefone = $_POST['telefone'];

    // Prepara a consulta SQL para inserir o serviço
    $sql = "INSERT INTO servicos (nome_do_servico, descricao, valorhora, telefone, id_prestador)
            VALUES (:nome_do_servico, :descricao, :valorhora, :telefone, :id_prestador)";

    $stmt = $pdo->prepare($sql);

    // Executa a consulta
    $stmt->execute([
        ':nome_do_servico' => $nome_do_servico,
        ':descricao' => $descricao,
        ':valorhora' => $valorhora,
        ':telefone' => $telefone,
        ':id_prestador' => $id_prestador,
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Serviço criado com sucesso!']);
}
?>
