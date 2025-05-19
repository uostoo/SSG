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
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os valores enviados pelo formulário
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Prepara e executa a consulta para verificar o usuário
    $sql = "SELECT id, senha, tipo FROM cliente WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Obtem os dados do usuário

        // Verifica a senha
        if (password_verify($senha, $user['senha'])) {
            // Verifica o tipo do usuário e armazena o ID na sessão
            if ($user['tipo'] === 'prestador') {
                $_SESSION['id_prestador'] = $user['id']; // Salva o ID do prestador
                header("Location: ../telaprestador/"); // Redireciona para a página do prestador
                exit();
            } elseif ($user['tipo'] === 'cliente') {
                $_SESSION['id_cliente'] = $user['id']; // Salva o ID do cliente
                header("Location: ../telacliente/"); // Redireciona para a página do cliente
                exit();
            } else {
                echo "Erro: Tipo de usuário desconhecido.";
            }
        } else {
            echo "Credenciais inválidas. Verifique seu email e senha.";
        }
    } else {
        echo "Usuário não encontrado. Verifique suas credenciais.";
    }

    $stmt->close(); // Fecha a consulta preparada
}

$conn->close(); // Fecha a conexão
?>
