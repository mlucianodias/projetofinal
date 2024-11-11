<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $senha = md5($_POST["senha"]);

    $sql = "SELECT * FROM usuarios WHERE nome = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION["logado"] = true;
        $_SESSION["user_id"] = $user["id"]; // Armazenar o ID do usuário
        $_SESSION["is_admin"] = $user["is_admin"];
        $_SESSION["nome"] = $user["nome"]; 
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}

?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login - Reserva de Salas</title>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-container p-4 rounded shadow-sm text-center">
        <h2>Acesso ao Sistema de Reservas</h2>
        <form action="login.php" method="post" class="mt-3">
            <?php if (isset($erro)) : ?>
                <div class="alert alert-danger"><?= $erro ?></div>
            <?php endif; ?>
            <div class="mb-3">
                <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuário" required>
            </div>
            <div class="mb-3">
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
    <footer>Powered by Luciano</footer>
</body>
</html>
