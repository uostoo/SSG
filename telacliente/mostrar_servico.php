<?php
// Conectar ao banco de dados
$host = 'localhost';
$dbname = 'ssg';
$username = 'root'; // Use seu usuário do banco de dados
$password = ''; // Use sua senha do banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os serviços disponíveis
    $sql = "
        SELECT s.id, s.nome_do_servico, s.descricao, s.valorhora, s.telefone, c.nome AS prestador_nome
        FROM servicos s
        JOIN cliente c ON s.id_prestador = c.id
        WHERE c.tipo = 'prestador'
    ";

    $stmt = $pdo->query($sql);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os dados no formato JSON
    echo json_encode($services);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erro ao buscar os serviços: " . $e->getMessage()]);
}
?>
