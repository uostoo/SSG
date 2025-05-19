<?php
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
?>
