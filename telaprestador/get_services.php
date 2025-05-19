<?php
include 'db.php';
session_start();

if (!isset($_SESSION['id_prestador'])) {
    echo json_encode([]);
    exit();
}

$id_prestador = $_SESSION['id_prestador'];
$services = [];

$sql = "SELECT s.id, s.nome_do_servico, s.descricao, s.valorhora, s.telefone
        FROM servicos s
        WHERE s.id_prestador = :id_prestador";
        
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_prestador' => $id_prestador]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $services[] = $row;
}

echo json_encode($services);
?>
