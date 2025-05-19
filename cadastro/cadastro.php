<?php
// Exibe erros para debug (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados (ajuste os dados de conexão)
$host = 'localhost'; // Ou o IP do servidor
$port = '3306'; // Porta padrão do MySQL (ajuste se necessário)
$dbname = 'ssg'; // Nome do seu banco de dados
$user = 'root'; // Usuário do MySQL
$password = ''; // Senha do MySQL

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Valida os campos
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');

        if (empty($username) || empty($email) || empty($password) || empty($tipo)) {
            die("Por favor, preencha todos os campos.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("E-mail inválido.");
        }

        if (strlen($password) < 8) {
            die("A senha deve ter pelo menos 8 caracteres.");
        }

        // Verifica se o e-mail já está registrado
        $stmt = $conn->prepare("SELECT id FROM cliente WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            die("O e-mail já está registrado. Tente fazer login.");
        }

        // Criptografa a senha
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insere os dados no banco de dados
        $stmt = $conn->prepare("INSERT INTO cliente (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
        $stmt->execute([
            'nome' => $username,
            'email' => $email,
            'senha' => $hashedPassword,
            'tipo' => $tipo
        ]);

        // Redireciona para a página de login
        header("Location: ../login/");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
