<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(['error' => 'Você precisa estar logado para agendar um serviço.']);
    exit;
}

// Conectar ao banco de dados
$host = 'localhost';
$dbname = 'ssg';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Receber dados do POST
    $serviceId = $_POST['serviceId'];
    $clientName = $_POST['clientName'];
    $serviceDate = $_POST['serviceDate'];
    $serviceDuration = $_POST['serviceDuration'];
    $clientId = $_POST['clientId']; // O ID do cliente vem da sessão

    // Buscar o ID do prestador associado ao serviço
    $stmtPrestador = $pdo->prepare('SELECT id_prestador FROM servicos WHERE id = ?');
    $stmtPrestador->execute([$serviceId]);
    $prestador = $stmtPrestador->fetch(PDO::FETCH_ASSOC);

    if (!$prestador) {
        echo json_encode(['error' => 'Serviço não encontrado.']);
        exit;
    }

    $prestadorId = $prestador['id_prestador'];

    // Inserir o agendamento no banco de dados
    $stmt = $pdo->prepare('INSERT INTO agendamentos (id_servico, id_cliente, id_prestador, tempo) VALUES (?, ?, ?, ?)');
    $stmt->execute([$serviceId, $clientId, $prestadorId, $serviceDate]);

    // Verificar se a inserção foi bem-sucedida
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Falha ao agendar o serviço.']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro de conexão: ' . $e->getMessage()]);
}
?>
