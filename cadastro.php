<?php
session_start();
require_once 'config.php';

// Verifique se o usuário está logado e é administrador
if (!isset($_SESSION['logado']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Tratamento do formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $usuario = $_POST["usuario"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $is_admin = isset($_POST["is_admin"]) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, usuario, senha, is_admin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nome, $usuario, $senha, $is_admin);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Cadastrar Usuário</title>
</head>
<body>
    <h2>Cadastrar Usuário</h2>
    <form action="cadastrar-usuario.php" method="post">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="usuario" placeholder="Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <label><input type="checkbox" name="is_admin"> Administrador</label>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
