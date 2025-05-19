<?php
session_start(); // Inicia a sessão

// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ssg";

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die(json_encode(['error' => 'Erro de conexão: ' . $conn->connect_error]));
}

// Verifica se o prestador está logado
if (isset($_SESSION['id_prestador'])) {
    $id_prestador = $_SESSION['id_prestador']; // ID do prestador

    // Consulta para pegar as notificações do prestador (agendamentos pendentes)
    $sql = "SELECT a.id, a.id_cliente, a.tempo, c.nome AS cliente_nome, s.nome_do_servico
            FROM agendamentos a
            JOIN cliente c ON a.id_cliente = c.id
            JOIN servicos s ON a.id_servico = s.id
            WHERE a.id_prestador = ? AND a.status = 'Pendente'"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_prestador);
    $stmt->execute();
    $result = $stmt->get_result();

    // Se houver notificações
    if ($result->num_rows > 0) {
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        echo json_encode($notifications); // Retorna o JSON com as notificações
    } else {
        // Retorna um array vazio para garantir a estrutura correta
        echo json_encode([]); // Retorna um array vazio
    }

    $stmt->close();
} else {
    // Se o prestador não estiver logado, retorna um erro
    echo json_encode(['error' => 'Usuário não autenticado']);
}

$conn->close();
?>
