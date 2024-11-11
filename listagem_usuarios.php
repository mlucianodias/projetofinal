<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["logado"]) || !$_SESSION["is_admin"]) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Usuários Cadastrados</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Administrador</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($usuario = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario["nome"]) ?></td>
                    <td><?= $usuario["is_admin"] ? 'Sim' : 'Não' ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $usuario["id"] ?>" class="btn btn-warning">Editar</a>
                        <a href="excluir_usuario.php?id=<?= $usuario["id"] ?>" class="btn btn-danger">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
