<?php
session_start();

$host = 'localhost';  // ou '127.0.0.1'
$dbname = 'ssg';
$username = 'root';  // usuário padrão do WAMP
$password = '';  // senha padrão do WAMP (geralmente em branco)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}


// Verifica se o usuário está logado e é cliente
if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(['success' => false, 'message' => 'Você precisa estar logado como cliente para virar prestador.']);
    exit;
}

$id_cliente = $_SESSION['id_cliente'];

// Confirmação recebida via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza o tipo de usuário no banco de dados usando PDO
    $sql = "UPDATE cliente SET tipo = 'prestador' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Atualiza as sessões
        $_SESSION['id_prestador'] = $_SESSION['id_cliente'];
        unset($_SESSION['id_cliente']); // Remove a sessão de cliente

        echo json_encode(['success' => true, 'redirect' => '../telaprestador/']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o tipo no banco de dados.']);
    }

    exit;
}

?>
